<?php
namespace App\Http\Controllers;
use App\Helpers\ActivityLogger;
use App\Models\CompanyInfo;
use App\Models\ContractCompany;
use App\Models\Currency;
use App\Models\Driver;
use App\Models\FinancialTransaction;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Settlement;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class SettlementController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:settlements.index', only: [
                'index',
            ]),
            new Middleware('permission:settlements.view', only: [
                'show',
            ]),
            new Middleware('permission:settlements.create', only: [
                'create',
                'store',
            ]),
            new Middleware('permission:settlements.edit', only: [
                'edit',
                'update',
            ]),
            new Middleware('permission:settlements.delete', only: [
                'destroy',
            ]),
            new Middleware('permission:settlements.pay', only: [
                'pay',
                'paySettlement',
            ]),
            new Middleware('permission:reports.settlement', only: [
                'print',
            ]),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Settlement::query()
            ->with('driver');
        $query->when(request('search'), function ($q) {
            $q->where(function ($query) {
                // Search settlement notes
                $query->where('notes', 'like', '%'.request('search').'%')
                    ->orWhere('settlement_num', 'like', '%'.request('search').'%')
                    // Search driver
                    ->orWhereHas('driver', function ($driver) {
                        $driver->where(function ($d) {
                            $d->where('name', 'like', '%'.request('search').'%')
                                ->orWhere('phone', 'like', '%'.request('search').'%');
                        });
                    });
            });
        });
        // Driver filter
        $query->when(request('driver_id'), function ($q) {
            $q->where('driver_id', request('driver_id'));
        });
        // Status filter
        $query->when(request('status'), function ($q) {
            $q->where('status', request('status'));
        });
        // Date from
        $query->when(request('date_from'), function ($q) {
            $q->whereDate('date', '>=', request('date_from'));
        });
        // Date to
        $query->when(request('date_to'), function ($q) {
            $q->whereDate('date', '<=', request('date_to'));
        });
        $settlements = $query
            ->latest('date')
            ->paginate(10)
            ->withQueryString();
        $drivers = Driver::where('is_active', true)
            ->orderBy('name')
            ->get();
        $defaultCurrency = Currency::where('is_default', true)
            ->select('symbol')
            ->first();
        return view('settlements.index', compact(
            'settlements',
            'drivers',
            'defaultCurrency'
        ));
    }
    public function print(Settlement $settlement)
    {
        $settlement->load([
            'driver',
            'orders.contractCompany',
        ]);
        $defaultCurrency = Currency::where('is_default', true)->first();
        $companyInfo = CompanyInfo::first();
        return view('settlements.print', compact(
            'settlement',
            'companyInfo',
            'defaultCurrency'
        ));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $drivers = Driver::where('is_active', 1)->get();
        $currencies = Currency::all();
        $companies = ContractCompany::where('is_active', 1)->get();
        $defaultCurrency = Currency::where('is_default', true)->first();
        return view('settlements.create', compact(
            'drivers',
            'currencies',
            'companies',
            'defaultCurrency'
        ));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'driver_id' => 'required|exists:drivers,id',
            'date' => 'required|date|before_or_equal:today',
            'notes' => 'nullable|string',
            'orders' => 'required|array|min:1',
            'orders.*.delivery_fee' => 'required|numeric|min:0',
            'orders.*.currency_id' => 'required|exists:currencies,id',
            'orders.*.currency_rate' => 'required|numeric|gt:0',
            'orders.*.contract_company_id' => 'nullable|exists:contract_companies,id',
        ]);
        DB::beginTransaction();
        try {
            $driver = Driver::findOrFail($validated['driver_id']);
            $driverPercentage = $driver->driver_percentage;
            $totalOrders = count($validated['orders']);
            $subtotal = 0;
            $driverTotal = 0;
            $companyTotal = 0;
            $deliveryTotal = 0;
            $contractCompanyTotal = 0;
            $settlement = Settlement::create([
                'driver_id' => $validated['driver_id'],
                'driver_percentage' => $driverPercentage,
                'date' => $validated['date'],
                'notes' => $validated['notes'],
                'total_orders' => 0,
                'driver_total' => 0,
                'company_total' => 0,
                'contract_company_total' => 0,
                'delivery_total' => 0,
                'subtotal' => 0,
                'status' => 'pending',
            ]);
            $orderIds = [];
            foreach ($validated['orders'] as $item) {
                $contractCompany = ContractCompany::find($item['contract_company_id']);
                $deliveryFee = $item['delivery_fee'];
                $deliveryFeeBase = $deliveryFee * $item['currency_rate'];
                $partnerAmount = 0;
                $partnerAmountBase = 0;
                if (! empty($item['contract_company_id'])) {
                    if ($contractCompany->fee_type == 'percentage') {
                        $partnerAmount = $deliveryFee * $contractCompany->percentage / 100;
                        $partnerAmountBase = $deliveryFeeBase * $contractCompany->percentage / 100;
                    } else {
                        $partnerAmountBase = $contractCompany->fixed_fee;
                        $partnerAmount = $partnerAmountBase / $item['currency_rate'];
                    }
                }
                $subtotalOrder = $deliveryFee - $partnerAmount;
                $subtotalOrderBase = $deliveryFeeBase - $partnerAmountBase;
                $driverAmount = $subtotalOrder * $driverPercentage / 100;
                $driverAmountBase = $subtotalOrderBase * $driverPercentage / 100;
                $companyAmount = $subtotalOrder - $driverAmount;
                $companyAmountBase = $subtotalOrderBase - $driverAmountBase;
                $order = Order::create([
                    'delivery_fee' => $deliveryFee,
                    'delivery_fee_base' => $deliveryFeeBase,
                    'contract_company_percentage' => $contractCompany->percentage ?? null,
                    'contract_company_fixed' => $contractCompany->fixed_fee ?? null,
                    'contract_company_amount' => $partnerAmount,
                    'contract_company_amount_base' => $partnerAmountBase,
                    'driver_amount' => $driverAmount,
                    'driver_amount_base' => $driverAmountBase,
                    'company_amount' => $companyAmount,
                    'company_amount_base' => $companyAmountBase,
                    'exchange_rate' => $item['currency_rate'],
                    'currency_id' => $item['currency_id'],
                    'driver_id' => $validated['driver_id'],
                    'contract_company_id' => $item['contract_company_id'] ?: null,
                ]);
                $orderIds[] = $order->id;
                // $deliveryTotal += $deliveryFeeBase;
                // $subtotal += $subtotalOrderBase;
                $driverTotal += $driverAmountBase;
                $companyTotal += $companyAmountBase;
                $contractCompanyTotal += $partnerAmountBase;
            }
            $DefaultCurrency = Currency::where('is_default', true)->first();
            if ($DefaultCurrency && $DefaultCurrency->rounding_unit > 0) {
                $roundingUnit = $DefaultCurrency->rounding_unit;
                $driverTotal = $this->roundCurrency($driverTotal, $roundingUnit);
                $companyTotal = $this->roundCurrency($companyTotal, $roundingUnit);
                $contractCompanyTotal = $this->roundCurrency($contractCompanyTotal, $roundingUnit);
            }
            $subtotal = $driverTotal + $companyTotal;
            $deliveryTotal = $subtotal + $contractCompanyTotal;
            $settlement->update([
                'total_orders' => $totalOrders,
                'driver_total' => $driverTotal,
                'company_total' => $companyTotal,
                'contract_company_total' => $contractCompanyTotal,
                'delivery_total' => $deliveryTotal,
                'subtotal' => $subtotal,
            ]);
            ActivityLogger::log(
                'created',
                $settlement,
                "Created settlement {$settlement->settlement_num}",
                [],
                $settlement->fresh()->toArray()
            );
            $settlement->orders()->attach($orderIds);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => __('messages.created_successfully', ['item' => __('messages.settlement')]),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e);
            return response()->json([
                'success' => false,
                'message' => __('messages.error_general'),
            ], 500);
        }
    }
    public function roundCurrency($amount, $unit)
    {
        if ($unit <= 0) {
            return $amount;
        }
        return round($amount / $unit) * $unit;
    }
    /**
     * Display the specified resource.
     */
    public function show(Settlement $settlement)
    {
        $settlement->load([
            'driver',
            'orders.contractCompany',
        ]);
        $defaultCurrency = Currency::where('is_default', true)->first();
        return view('settlements.view', compact('settlement', 'defaultCurrency'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Settlement $settlement)
    {
        return view('settlements.edit', compact('settlement'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Settlement $settlement)
    {
        $validated = $request->validate([
            'notes' => 'nullable|string',
            'date' => 'required|date',
        ]);
        $old = $settlement->toArray();
        $settlement->update([
            'notes' => $validated['notes'],
            'date' => $validated['date'],
        ]);
        ActivityLogger::log(
            'updated',
            $settlement,
            "Updated settlement {$settlement->settlement_num}",
            $old,
            $settlement->fresh()->toArray()
        );
        return redirect()
            ->route('settlements.index')
            ->with('success', __('messages.updated_successfully', [
                'item' => __('messages.settlement'),
            ]));
    }
    public function pay(Request $request)
    {
        $settlements = Settlement::whereIn('id', $request->settlements)
            ->where('status', 'pending')
            ->get();
        if ($settlements->isEmpty()) {
            return response()->json([
                'message' => __('messages.invalid', ['item' => __('messages.settlement')]),
            ], 400);
        }
        $total = $settlements->sum('company_total');
        $defaultCurrency = Currency::where('is_default', true)->firstOrFail();
        $defaultPaymentMethod = PaymentMethod::where('is_default', true)->firstOrFail();
        DB::transaction(function () use ($settlements, $defaultCurrency, $defaultPaymentMethod, &$total) {
            foreach ($settlements as $settlement) {
                $old = $settlement->toArray();
                $settlement->update([
                    'status' => 'closed',
                ]);
                ActivityLogger::log(
                    'Closed',
                    $settlement,
                    "Closed settlement {$settlement->settlement_num}",
                    $old,
                    $settlement->fresh()->toArray()
                );
                FinancialTransaction::create([
                    'date' => now()->toDateString(),
                    'type' => 'settlement',
                    'amount' => $settlement->company_total,
                    'amount_base' => $settlement->company_total,
                    'currency_id' => $defaultCurrency->id,
                    'exchange_rate' => $defaultCurrency->rate,
                    'direction' => 'in',
                    'status' => 'completed',
                    'notes' => 'Settlement #'.$settlement->settlement_num.
                        ' | Driver: '.$settlement->driver?->name.
                        ' | Amount: '.$settlement->company_total.
                        ' | Closed',
                    'driver_id' => $settlement->driver_id,
                    'settlement_id' => $settlement->id,
                    'payment_method_id' => $defaultPaymentMethod->id,
                ]);
            }
        });
        return response()->json([
            'message' => __('messages.marked_received_total_company', [
                'total' => number_format($total, 2).' '.$defaultCurrency->symbol,
            ]),
        ]);
    }
    public function paySettlement(Request $request)
    {
        $request->validate([
            'settlements' => 'required|exists:settlements,id',
        ]);
        $settlement = Settlement::where('id', $request->settlements)
            ->where('status', 'pending')
            ->first();
        if (! $settlement) {
            return back()->with('error', __('messages.invalid', [
                'item' => __('messages.settlement'),
            ]));
        }
        $defaultCurrency = Currency::where('is_default', true)->firstOrFail();
        $defaultPaymentMethod = PaymentMethod::where('is_default', true)->firstOrFail();
        DB::transaction(function () use ($settlement, $defaultCurrency, $defaultPaymentMethod) {
            $old = $settlement->toArray();
            $settlement->update([
                'status' => 'closed',
            ]);
            ActivityLogger::log(
                'closed',
                $settlement,
                "Closed settlement {$settlement->settlement_num}",
                $old,
                $settlement->fresh()->toArray()
            );
            FinancialTransaction::create([
                'date' => now()->toDateString(),
                'type' => 'settlement',
                'amount' => $settlement->company_total,
                'amount_base' => $settlement->company_total,
                'currency_id' => $defaultCurrency->id,
                'exchange_rate' => $defaultCurrency->rate,
                'direction' => 'in',
                'status' => 'completed',
                'notes' => 'Settlement #'.$settlement->settlement_num.
                    ' | Driver: '.$settlement->driver->name.
                    ' | Amount: '.$settlement->company_total.
                    ' | Closed',
                'driver_id' => $settlement->driver_id,
                'settlement_id' => $settlement->id,
                'payment_method_id' => $defaultPaymentMethod->id,
            ]);
        });
        return back()->with('success', __('messages.marked_received_total_company', [
            'total' => number_format($settlement->company_total, 2).' '.$defaultCurrency->symbol,
        ]));
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Settlement $settlement)
    {
        if ($settlement->status === 'closed') {
            return back()->with('error', __('messages.cant_delete', [
                'item' => __('messages.settlement'),
            ]));
        }
        $old = $settlement->toArray();
        ActivityLogger::log(
            'deleted',
            $settlement,
            "Deleted settlement {$settlement->settlement_num}",
            $old,
            []
        );
        $settlement->delete();
        return back()->with('success', __('messages.deleted_successfully', [
            'item' => __('messages.settlement'),
        ]));
    }
}
