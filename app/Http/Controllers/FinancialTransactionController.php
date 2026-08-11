<?php
namespace App\Http\Controllers;
use App\Helpers\ActivityLogger;
use App\Models\Collection;
use App\Models\ContractCompany;
use App\Models\Currency;
use App\Models\Driver;
use App\Models\FinancialTransaction;
use App\Models\PaymentMethod;
use App\Models\Settlement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
class FinancialTransactionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:transactions.index', only: [
                'index',
            ]),
            new Middleware('permission:transactions.create', only: [
                'create',
                'store'
            ]),
            new Middleware('permission:transactions.cancel', only: [
                'update'
            ]),
            new Middleware('permission:reports.transaction', only: [
                'print'
            ]),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = FinancialTransaction::query();
        $query->when(request('search'), function ($q) {
            $q->where(function ($query) {
                // Search settlement notes
                $query->where('notes', 'like', '%' . request('search') . '%')
                    ->orWhere('transaction_num', 'like', '%' . request('search') . '%')
                    // Search driver
                    ->orWhereHas('driver', function ($driver) {
                        $driver->where(function ($d) {
                            $d->where('name', 'like', '%' . request('search') . '%');
                        });
                    })
                    ->orWhereHas('contractCompany', function ($contractCompany) {
                        $contractCompany->where(function ($d) {
                            $d->where('name', 'like', '%' . request('search') . '%');
                        });
                    })
                    ->orWhereHas('collection', function ($collection) {
                        $collection->where(function ($d) {
                            $d->where('collection_num', 'like', '%' . request('search') . '%');
                        });
                    })
                    ->orWhereHas('settlement', function ($settlement) {
                        $settlement->where(function ($d) {
                            $d->where('settlement_num', 'like', '%' . request('search') . '%');
                        });
                    });
            });
        });
        // Driver filter
        $query->when(request('currency'), function ($q) {
            $q->where('currency_id', request('currency'));
        });
        $query->when(request('paymentMethod'), function ($q) {
            $q->where('payment_method_id', request('paymentMethod'));
        });
        // Status filter
        $query->when(request('status'), function ($q) {
            $q->where('status', request('status'));
        });
        $query->when(request('direction'), function ($q) {
            $q->where('direction', request('direction'));
        });
        $query->when(request('type'), function ($q) {
            $q->where('type', request('type'));
        });
        // Date from
        $query->when(request('date_from'), function ($q) {
            $q->whereDate('date', '>=', request('date_from'));
        });
        // Date to
        $query->when(request('date_to'), function ($q) {
            $q->whereDate('date', '<=', request('date_to'));
        });
        $summary = (clone $query)
            ->where('status', 'completed')
            ->reorder()
            ->selectRaw("
        SUM(CASE WHEN direction = 'in' THEN amount_base ELSE 0 END) as total_in,
        SUM(CASE WHEN direction = 'out' THEN amount_base ELSE 0 END) as total_out,
        COUNT(*) as transactions_count
    ")
            ->first();
        $transactions = $query
            ->latest('date')
            ->paginate(10)
            ->withQueryString();
        $summary->total_in ??= 0;
        $summary->total_out ??= 0;
        $summary->transactions_count ??= 0;
        $summary->net = $summary->total_in - $summary->total_out;
        $currencies = Currency::all();
        $paymentMethods = PaymentMethod::all();
        return view('transactions.index', compact('transactions', 'currencies', 'paymentMethods', 'summary'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $currencies = Currency::all();
        $drivers = Driver::where('is_active', true)->get();
        $partners = ContractCompany::where('is_active', true)->get();
        $paymentMethods = PaymentMethod::where('is_active', true)->get();
        $users = User::where('is_active', true)->get();
        return view('transactions.create', compact('currencies', 'drivers', 'partners', 'paymentMethods', 'users'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'type' => 'required|in:payment,receipt,others,adjustment,refund,expenses',
            'direction' => 'required|in:in,out',
            'currency' => 'required|exists:currencies,id',
            'amount' => 'required|numeric|min:0',
            'partner' => 'nullable|exists:contract_companies,id',
            'driver' => 'nullable|exists:drivers,id',
            'user' => 'nullable|exists:users,id',
            'payment_method' => 'nullable|exists:payment_methods,id',
            'notes' => 'nullable|string'
        ]);
        $currency = Currency::find($validated['currency']);
        $amount_base = $validated['amount'] * $currency->rate;
        if ($validated['type'] === 'payment' | $validated['type'] === 'expenses' | $validated['type'] === 'refund') {
            $direction = 'out';
        } elseif ($validated['type'] === 'receipt') {
            $direction = 'in';
        } else {
            $direction = $validated['direction'];
        }
        $transaction = FinancialTransaction::create([
            'date' => $validated['date'],
            'type' => $validated['type'],
            'amount' => $validated['amount'],
            'amount_base' => $amount_base,
            'currency_id' => $validated['currency'],
            'exchange_rate' => $currency->rate,
            'direction' => $direction,
            'status' => 'completed',
            'notes' => $validated['notes'],
            'driver_id' => $validated['driver'],
            'user_id' => $validated['user'],
            'contract_company_id' => $validated['partner'],
            'payment_method_id' => $validated['payment_method'],
        ]);
        ActivityLogger::log(
            'created',
            $transaction,
            "Created transaction {$transaction->transaction_num}",
            [],
            $transaction->fresh()->toArray()
        );
        return redirect()
            ->route('transactions.index')
            ->with('success',  __('messages.created_successfully', [
                'item' => __('messages.transaction')
            ]));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FinancialTransaction $transaction)
    {
        if ($request->actionType === 'cancel') {
            $transaction->update([
                'status' => 'canceled',
            ]);
            ActivityLogger::log(
                'canceled',
                $transaction,
                "Canceled transaction {$transaction->transaction_num}",
            );
            return back()
                ->with('success', __('messages.canceled_successfully', [
                    'item' => __('messages.transaction')
                ]));
        } elseif ($request->actionType === 'restore') {
            $transaction->update([
                'status' => 'completed',
            ]);
            ActivityLogger::log(
                'restored',
                $transaction,
                "Restored transaction {$transaction->transaction_num}",
            );
            return back()
                ->with('success', __('messages.restored_successfully', [
                    'item' => __('messages.transaction')
                ]));
        } else {
            return back()->with('error',  __('messages.error_general'));
        }
    }
    public function print(Request $request)
    {
        $query = FinancialTransaction::with('driver');
        $query->when(request('search'), function ($q) {
            $q->where(function ($query) {
                // Search settlement notes
                $query->where('notes', 'like', '%' . request('search') . '%')
                    ->orWhere('transaction_num', 'like', '%' . request('search') . '%')
                    // Search driver
                    ->orWhereHas('driver', function ($driver) {
                        $driver->where(function ($d) {
                            $d->where('name', 'like', '%' . request('search') . '%');
                        });
                    })
                    ->orWhereHas('contractCompany', function ($contractCompany) {
                        $contractCompany->where(function ($d) {
                            $d->where('name', 'like', '%' . request('search') . '%');
                        });
                    })
                    ->orWhereHas('collection', function ($collection) {
                        $collection->where(function ($d) {
                            $d->where('collection_num', 'like', '%' . request('search') . '%');
                        });
                    })
                    ->orWhereHas('settlement', function ($settlement) {
                        $settlement->where(function ($d) {
                            $d->where('settlement_num', 'like', '%' . request('search') . '%');
                        });
                    });
            });
        });
        // Driver filter
        $query->when(request('currency'), function ($q) {
            $q->where('currency_id', request('currency'));
        });
        $query->when(request('paymentMethod'), function ($q) {
            $q->where('payment_method_id', request('paymentMethod'));
        });
        // Status filter
        $query->when(request('status'), function ($q) {
            $q->where('status', request('status'));
        });
        $query->when(request('direction'), function ($q) {
            $q->where('direction', request('direction'));
        });
        $query->when(request('type'), function ($q) {
            $q->where('type', request('type'));
        });
        // Date from
        $query->when(request('date_from'), function ($q) {
            $q->whereDate('date', '>=', request('date_from'));
        });
        // Date to
        $query->when(request('date_to'), function ($q) {
            $q->whereDate('date', '<=', request('date_to'));
        });
        $transactions = $query
            ->orderBy('date', 'desc')
            ->get();
        $summary = (clone $query)
            ->where('status', 'completed')
            ->reorder()
            ->selectRaw("
            SUM(CASE WHEN direction='in' THEN amount_base ELSE 0 END) total_in,
            SUM(CASE WHEN direction='out' THEN amount_base ELSE 0 END) total_out,
            COUNT(*) transactions_count
        ")
            ->first();
        $summary->net = $summary->total_in - $summary->total_out;
        $currency = Currency::where('is_default', true)->select('symbol')->first();
        return view('transactions.print', compact(
            'transactions',
            'summary',
            'currency'
        ));
    }
}
