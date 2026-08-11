<?php
namespace App\Http\Controllers;
use App\Models\Collection;
use App\Models\Currency;
use App\Models\Settlement;
use App\Models\Driver;
use App\Models\FinancialTransaction;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
class DashboardController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:dashboard.index', only: [
                'index',
            ]),
        ];
    }
    public function index()
    {
        $today = Carbon::today();
        $month = Carbon::now();
        $year = Carbon::now();
        $defaultCurrecy = Currency::where('is_default', true)->first();
        /*
        |--------------------------------------------------------------------------
        | ORDERS (from settlements)
        |--------------------------------------------------------------------------
        */
        $ordersToday = Settlement::whereDate('date', $today)->sum('total_orders');
        $ordersMonth = Settlement::whereMonth('date', $month->month)
            ->whereYear('date', $month->year)
            ->sum('total_orders');
        /*
        |--------------------------------------------------------------------------
        | FINANCIAL (settlements snapshot)
        |--------------------------------------------------------------------------
        */
        $financialToday = Settlement::whereDate('date', $today)
            ->selectRaw('
                SUM(delivery_total) as revenue,
                SUM(company_total) as company_earnings,
                SUM(driver_total) as driver_profit,
                SUM(contract_company_total) as partner_share
            ')
            ->first();
        $financialMonth = Settlement::whereMonth('date', $month->month)
            ->whereYear('date', $month->year)
            ->selectRaw('
                SUM(delivery_total) as revenue,
                SUM(company_total) as company_earnings,
                SUM(driver_total) as driver_profit,
                SUM(contract_company_total) as partner_share
            ')
            ->first();
        /*
        |--------------------------------------------------------------------------
        | FINANCIAL (collections snapshot)
        |--------------------------------------------------------------------------
        */
        $collectionToday = Collection::whereDate('date', $today)
            ->selectRaw('
                 SUM(driver_amount_base) as driver_collections,
                SUM(company_amount_base) as company_collections,
                 SUM(received_amount_base) as received_collections
            ')
            ->first();
        $collectionMonth = Collection::whereMonth('date', $month->month)
            ->selectRaw('
                SUM(driver_amount_base) as driver_collections,
                SUM(company_amount_base) as company_collections,
                SUM(received_amount_base) as received_collections
            ')
            ->first();
        /*
        |--------------------------------------------------------------------------
        | FINANCIAL (expenses snapshot)
        |--------------------------------------------------------------------------
        */
        $expensesToday = FinancialTransaction::whereDate('date', $today)
            ->where('type', 'expenses')
            ->where('status', 'completed')
            ->sum('amount_base');
        $expensesMonth = FinancialTransaction::whereMonth('date', $month->month)
            ->where('type', 'expenses')
            ->where('status', 'completed')
            ->sum('amount_base');
        /*
        |--------------------------------------------------------------------------
        | CASH FLOW (transactions)
        |--------------------------------------------------------------------------
        */
        $cashIn = FinancialTransaction::where('direction', 'in')->where('status', 'completed')->sum('amount_base');
        $cashOut = FinancialTransaction::where('direction', 'out')->where('status', 'completed')->sum('amount_base');
        $netBalance = $cashIn - $cashOut;
        $cashFlowChart = [
            'labels' => [
                __('dashboard.inflow'),
                __('dashboard.outflow'),
                __('dashboard.net_balance')
            ],
            'data' => [
                $cashIn,
                $cashOut,
                $netBalance,
            ],
        ];
        $transactionsByType = FinancialTransaction::selectRaw('type, SUM(amount_base) as total')
            ->whereMonth('date', $month->month)
            ->whereYear('date', $month->year)
            ->where('status', 'completed')
            ->groupBy('type')
            ->pluck('total', 'type')
            ->toArray();
        $types = [
            'payment',
            'receipt',
            'expenses',
            'adjustment',
            'refund',
            'collection',
            'settlement',
            'other'
        ];
        $tbt = [];
        foreach ($types as $type) {
            $tbt[] = [
                'type' => __("dashboard.types.$type"),
                'total' => $transactionsByType[$type] ?? 0,
            ];
        }
        /*
        |--------------------------------------------------------------------------
        | SETTLEMENTS
        |--------------------------------------------------------------------------
        */
        $pendingSettlementsCount = Settlement::where('status', 'pending')->whereMonth('date', $month->month)->count();
        $closedSettlementsCount = Settlement::where('status', 'closed')->whereMonth('date', $month->month)->count();
        $pendingCollectionsCount = Collection::where('status', 'pending')->whereMonth('date', $month->month)->count();
        $paidCollectionsCount = Collection::where('status', 'paid')->whereMonth('date', $month->month)->count();
        $pendingSettlementsAmount = Settlement::whereMonth('date', $month->month)
            ->where('status', 'pending')
            ->SUM('company_total');
        $closedSettlementsAmount = Settlement::whereMonth('date', $month->month)
            ->where('status', 'closed')
            ->SUM('company_total');
        $pendingCollectionsAmount = Collection::whereMonth('date', $month->month)
            ->where('status', 'pending')
            ->SUM('company_amount_base');
        $paidCollectionsAmount = Collection::whereMonth('date', $month->month)
            ->where('status', 'paid')
            ->SUM('company_amount_base');
        /*
|--------------------------------------------------------------------------
| Revenue Trend
|--------------------------------------------------------------------------
*/
        $revenueDaily = [];
        for ($day = 1; $day <= Carbon::now()->daysInMonth; $day++) {
            $date = Carbon::create($year->year, $month->month, $day)->format('Y-m-d');
            $revenueDaily[] = [
                'label' => $day,
                'value' => Settlement::whereDate('date', $date)
                    ->sum('delivery_total')
            ];
        }
        $revenueMonthly = [];
        for ($m = 1; $m <= 12; $m++) {
            $revenueMonthly[] = [
                'label' => Carbon::create($year->year, $m, 1)->format('M'),
                'value' => Settlement::whereYear('date', $year->year)
                    ->whereMonth('date', $m)
                    ->sum('delivery_total')
            ];
        }
        /*
|--------------------------------------------------------------------------
| Profit Trend
|--------------------------------------------------------------------------
*/
        $profitDaily = [];
        for ($day = 1; $day <= Carbon::now()->daysInMonth; $day++) {
            $date = Carbon::create($year->year, $month->month, $day)->format('Y-m-d');
            $income =
                Settlement::whereDate('date', $date)
                ->sum('company_total')
                +
                Collection::whereDate('date', $date)
                ->sum('company_amount');
            $expenses = FinancialTransaction::whereDate('date', $date)
                ->where('type', 'expenses')
                ->where('status', 'completed')
                ->sum('amount_base');
            $profitDaily[] = [
                'label' => $day,
                'value' => $income - $expenses
            ];
        }
        $profitMonthly = [];
        for ($m = 1; $m <= 12; $m++) {
            $income =
                Settlement::whereYear('date', $year->year)
                ->whereMonth('date', $m)
                ->sum('company_total')
                +
                Collection::whereYear('date', $year->year)
                ->whereMonth('date', $m)
                ->sum('company_amount');
            $expenses = FinancialTransaction::whereYear('date', $year->year)
                ->whereMonth('date', $m)
                ->where('type', 'expenses')
                ->where('status', 'completed')
                ->sum('amount_base');
            $profitMonthly[] = [
                'label' => Carbon::create($year->year, $m, 1)->format('M'),
                'value' => $income - $expenses
            ];
        }
        /*
|--------------------------------------------------------------------------
| Orders Trend
|--------------------------------------------------------------------------
*/
        $ordersDaily = [];
        for ($day = 1; $day <= Carbon::now()->daysInMonth; $day++) {
            $date = Carbon::create($year->year, $month->month, $day)->format('Y-m-d');
            $ordersDaily[] = [
                'label' => $day,
                'value' => Settlement::whereDate('date', $date)
                    ->count()
            ];
        }
        $ordersMonthly = [];
        for ($m = 1; $m <= 12; $m++) {
            $ordersMonthly[] = [
                'label' => Carbon::create($year->year, $m, 1)->format('M'),
                'value' => Settlement::whereYear('date', $year->year)
                    ->whereMonth('date', $m)
                    ->count()
            ];
        }
        /*
|--------------------------------------------------------------------------
| Company Earnings Trend
|--------------------------------------------------------------------------
*/
        $companyEarningsDaily = [];
        for ($day = 1; $day <= Carbon::now()->daysInMonth; $day++) {
            $date = Carbon::create($year->year, $month->month, $day)->format('Y-m-d');
            $companyEarningsDaily[] = [
                'label' => $day,
                'value' => Settlement::whereDate('date', $date)
                    ->sum('company_total')
            ];
        }
        $companyEarningsMonthly = [];
        for ($m = 1; $m <= 12; $m++) {
            $companyEarningsMonthly[] = [
                'label' => Carbon::create($year->year, $m, 1)->format('M'),
                'value' => Settlement::whereYear('date', $year->year)
                    ->whereMonth('date', $m)
                    ->sum('company_total')
            ];
        }
        //      /* |--------------------------------------------------------------------------
        // | TOP DRIVERS
        // |--------------------------------------------------------------------------
        // */
        $topEarningDrivers = Driver::select(
            'drivers.id',
            'drivers.name',
            DB::raw('SUM(settlements.driver_total) as earnings')
        )
            ->join('settlements', 'drivers.id', '=', 'settlements.driver_id')
            ->where('drivers.is_active', 1)
            ->groupBy('drivers.id', 'drivers.name')
            ->orderByDesc('earnings')
            ->limit(5)
            ->get();
        $topDeliveringDrivers = Driver::select(
            'drivers.id',
            'drivers.name',
            DB::raw('SUM(settlements.total_orders) as deliveries')
        )
            ->join('settlements', 'drivers.id', '=', 'settlements.driver_id')
            ->where('drivers.is_active', 1)
            ->groupBy('drivers.id', 'drivers.name')
            ->orderByDesc('deliveries')
            ->limit(5)
            ->get();
        return view('dashboard.index', compact(
            'topEarningDrivers',
            'topDeliveringDrivers',
            'ordersDaily',
            'ordersMonthly',
            'companyEarningsDaily',
            'companyEarningsMonthly',
            'revenueDaily',
            'revenueMonthly',
            'profitDaily',
            'profitMonthly',
            'paidCollectionsAmount',
            'pendingCollectionsAmount',
            'closedSettlementsAmount',
            'pendingSettlementsAmount',
            'collectionMonth',
            'tbt',
            'cashFlowChart',
            'collectionToday',
            'expensesMonth',
            'expensesToday',
            'ordersMonth',
            'defaultCurrecy',
            'ordersToday',
            'financialMonth',
            'financialToday',
            'pendingCollectionsCount',
            'pendingSettlementsCount',
            'closedSettlementsCount',
            'paidCollectionsCount'
        ));
    }
}
