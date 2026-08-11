<?php
namespace App\Http\Controllers;
use App\Helpers\ActivityLogger;
use App\Models\Collection;
use App\Models\Currency;
use App\Models\Driver;
use App\Models\FinancialTransaction;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
class CollectionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:collections.index', only: [
                'index',
            ]),
            new Middleware('permission:collections.create', only: [
                'create',
                'store'
            ]),
            new Middleware('permission:collections.pay', only: [
                'update'
            ]),
            new Middleware('permission:collections.delete', only: [
                'destroy'
            ]),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Collection::query()
            ->when(request('search'), function ($q) {
                $q->where('collection_num', 'like', '%' . request('search') . '%')
                    ->orWhere('notes', 'like', '%' . request('search') . '%');
            })
            ->when(request('payment_method_id'), function ($q) {
                $q->where('payment_method_id', request('payment_method_id'));
            })
            ->when(request('driver_id'), function ($q) {
                $q->where('driver_id', request('driver_id'));
            })
            ->when(request('currency_id'), function ($q) {
                $q->where('currency_id', request('currency_id'));
            })
            ->when(request('status'), function ($q) {
                $q->where('status', request('status'));
            })
            ->when(request('from'), function ($q) {
                $q->whereDate('date', '>=', request('from'));
            })
            ->when(request('to'), function ($q) {
                $q->whereDate('date', '<=', request('to'));
            });
        $collections = $query->orderBy('date', 'desc')->paginate(10)->withQueryString();
        $drivers = Driver::get();
        $paymentMethods = PaymentMethod::all();
        $currencies = Currency::all();
        return view('collections.index', compact('collections', 'drivers', 'paymentMethods', 'currencies'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $drivers = Driver::where('is_active', true)->get();
        $paymentMethods = PaymentMethod::where('is_active', true)->get();
        $currencies = Currency::all();
        return view('collections.create', compact('drivers', 'paymentMethods', 'currencies'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'driver' => 'required|exists:drivers,id',
            'payment_method' => 'required|exists:payment_methods,id',
            'currency' => 'required|exists:currencies,id',
            'total_amount' => 'required|numeric|min:0',
            'driver_amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'notes' => 'nullable|string',
        ]);
        if ($validated['total_amount'] < $validated['driver_amount']) {
            return back()
                ->withErrors([
                    'driver_amount' => 'Driver amount cannot exceed the total amount.',
                    'total_amount'  => 'Total amount must be greater than or equal to driver amount.',
                ])
                ->withInput();
        }
        DB::beginTransaction();
        try {
            $company_amount = $validated['total_amount'] - $validated['driver_amount'];
            $currency = Currency::select('rate')
                ->where('id', $validated['currency'])
                ->first();
            $rate = $currency->rate;
            $amount_base = $validated['total_amount'] * $rate;
            $driver_amount_base = $validated['driver_amount'] * $rate;
            $company_amount_base = $company_amount * $rate;
            $collection = Collection::create([
                'driver_id' => $validated['driver'],
                'payment_method_id' => $validated['payment_method'],
                'currency_id' => $validated['currency'],
                'notes' => $validated['notes'],
                'date' => $validated['date'],
                'received_amount' => $validated['total_amount'],
                'driver_amount' => $validated['driver_amount'],
                'company_amount' => $company_amount,
                'received_amount_base' => $amount_base,
                'driver_amount_base' => $driver_amount_base,
                'company_amount_base' => $company_amount_base,
                'exchange_rate' => $rate,
                'status' => 'pending',
            ]);
            ActivityLogger::log(
                'created',
                $collection,
                "Created collection {$collection->name}",
                [],
                $collection->fresh()->toArray()
            );
            FinancialTransaction::create([
                'date' => $validated['date'],
                'type' => 'collection',
                'amount' => $validated['total_amount'],
                'amount_base' => $amount_base,
                'currency_id' => $validated['currency'],
                'exchange_rate' => $rate,
                'direction' => 'in',
                'status' => 'completed',
                'notes' =>
                'Collection #' . $collection->id .
                    ' | Driver: ' . $collection->driver->name .
                    ' | Amount: ' . $validated['total_amount'] . '| Received',
                'driver_id' => $validated['driver'],
                'collection_id' => $collection->id,
                'payment_method_id' => $validated['payment_method'],
            ]);
            DB::commit();
            return redirect()
                ->route('collections.index')
                ->with('success', __('messages.created_successfully', ['item' => __('messages.collection')]));
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Collection store failed', [
                'error' => $e->getMessage(),
                'data' => $validated,
            ]);
            return back()
                ->with('error', __('messages.error_saving', ['item' => __('messages.collection')]))
                ->withInput();
        }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Collection $collection)
    {
        $validated = $request->validate([
            'collection' => 'required|exists:collections,id',
        ]);
        // safety check (optional but cleaner)
        if ((int) $validated['collection'] !== $collection->id) {
            return back()->with('error', __('messages.invalid', ['item' => __('messages.collection')]));
        }
        // prevent double payment transaction
        if ($collection->status === 'paid') {
            return back()->with('error', __('messages.error_paid', ['item' => __('messages.collection')]));
        }
        DB::beginTransaction();
        try {
            $collection->update([
                'status' => 'paid',
            ]);
            ActivityLogger::log(
                'payment',
                $collection,
                "Paid collection {$collection->collection_num}",
            );
            FinancialTransaction::create([
                'date' => now()->toDateString(),
                'type' => 'payment',
                'amount' => $collection->received_amount,
                'amount_base' =>  $collection->received_amount_base,
                'currency_id' => $collection->currency_id,
                'exchange_rate' => $collection->exchange_rate,
                'direction' => 'out',
                'status' => 'completed',
                'notes' =>
                'Collection #' . $collection->id .
                    ' | Driver: ' . $collection->driver->name .
                    ' | Amount: ' . $collection->received_amount .
                    ' | Paid',
                'driver_id' => $collection->driver_id,
                'collection_id' => $collection->id,
                'payment_method_id' => $collection->payment_method_id,
            ]);
            DB::commit();
            return back()->with('success', __('messages.success_paid', ['item' => __('messages.collection')]));
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Collection payment failed', [
                'error' => $e->getMessage(),
                'collection_id' => $collection->id,
            ]);
            return back()->with('error', __('messages.general_error'));
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Collection $collection)
    {
        if ($collection->status === 'paid') {
            return back()->with('error', __('messages.cant_delete', ['item' => __('messages.paid_collection')]));
        }
        DB::transaction(function () use ($collection) {
            FinancialTransaction::where('collection_id', $collection->id)
                ->delete();
            $old = $collection->toArray();
            ActivityLogger::log(
                'deleted',
                $collection,
                "Deleted collection {$collection->collection_num}",
                $old,
                []
            );
            $collection->delete();
        });
        return back()->with('success', __('messages.deleted_successfully', ['item' => __('messages.collection')]));
    }
}
