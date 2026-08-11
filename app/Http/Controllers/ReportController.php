<?php
namespace App\Http\Controllers;
use App\Models\CompanyInfo;
use App\Models\ContractCompany;
use App\Models\Currency;
use App\Models\Driver;
use App\Models\Settlement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
class ReportController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:reports.drivers', only: [
                'driverReports',
                'driverReportsPrint'
            ]),
            new Middleware('permission:reports.partners', only: [
                'partnerReport',
                'partnerReportPrint'
            ]),
            new Middleware('permission:creports.company', only: [
                'companyReport',
                'companyReportPrint'
            ]),
        ];
    }
    public function driverReports(Request $request)
    {
        $query = Settlement::with([
            'driver',
            'orders'
        ]);
        // Settlement date filter
        if ($request->filled('from')) {
            $query->whereDate('date', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('date', '<=', $request->to);
        }
        // Multiple drivers filter
        if ($request->filled('drivers')) {
            $query->whereIn(
                'driver_id',
                $request->drivers
            );
        }
        $settlements = $query->get();
        // Group by driver
        $driversReport = $settlements
            ->groupBy('driver_id')
            ->map(function ($items) {
                return (object) [
                    'driver' => $items->first()->driver,
                    'orders_count' => $items->sum(function ($settlement) {
                        return $settlement->orders->count();
                    }),
                    'earnings' => $items->sum('driver_total'),
                    'last_date' => $items->max('date'),
                ];
            });
        // Sorting
        $sort = $request->get('sort', 'earnings');
        $direction = $request->get('direction', 'desc');
        $driversReport = $driversReport->sortBy(
            function ($item) use ($sort) {
                return match ($sort) {
                    'orders_count' => $item->orders_count,
                    'earnings' => $item->earnings,
                    'last_date' => $item->last_date,
                    'driver_name' => $item->driver?->name,
                    default => $item->earnings,
                };
            },
            SORT_REGULAR,
            $direction === 'desc'
        );
        // Cards
        $summary = [
            'drivers_count' => $driversReport->count(),
            'orders_count' => $driversReport->sum('orders_count'),
            'earnings' => $driversReport->sum('earnings'),
        ];
        $drivers = Driver::orderBy('name')->get();
        $currency = Currency::where('is_default', true)->select('symbol')->first();
        return view(
            'reports.drivers',
            compact(
                'driversReport',
                'drivers',
                'summary',
                'currency'
            )
        );
    }
    public function driverReportsPrint(Request $request)
    {
        $query = Settlement::with([
            'driver',
            'orders'
        ]);
        // Settlement date filter
        if ($request->filled('from')) {
            $query->whereDate('date', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('date', '<=', $request->to);
        }
        // Drivers filter
        if ($request->filled('drivers')) {
            $query->whereIn(
                'driver_id',
                $request->drivers
            );
        }
        $settlements = $query->get();
        $driversReport = $settlements
            ->groupBy('driver_id')
            ->map(function ($items) {
                return (object) [
                    'driver' => $items->first()->driver,
                    'orders_count' => $items->sum(function ($settlement) {
                        return $settlement->orders->count();
                    }),
                    'earnings' => $items->sum('driver_total'),
                    'last_date' => $items->max('date'),
                ];
            });
        // Same sorting
        $sort = $request->get('sort', 'earnings');
        $direction = $request->get('direction', 'desc');
        $driversReport = $driversReport->sortBy(
            function ($item) use ($sort) {
                return match ($sort) {
                    'orders_count' => $item->orders_count,
                    'earnings' => $item->earnings,
                    'last_date' => $item->last_date,
                    'driver_name' => $item->driver?->name,
                    default => $item->earnings,
                };
            },
            SORT_REGULAR,
            $direction === 'desc'
        );
        $summary = [
            'drivers_count' => $driversReport->count(),
            'orders_count' => $driversReport->sum('orders_count'),
            'earnings' => $driversReport->sum('earnings'),
        ];
        $currency = Currency::where('is_default', true)->select('symbol')->first();
        $companyInfo = CompanyInfo::first();
        return view(
            'reports.print.drivers',
            compact(
                'currency',
                'driversReport',
                'summary',
                'companyInfo'
            )
        );
    }
    public function partnerReports(Request $request)
    {
        $query = DB::table('settlements')
            ->join(
                'order_settlement',
                'order_settlement.settlement_id',
                '=',
                'settlements.id'
            )
            ->join(
                'orders',
                'orders.id',
                '=',
                'order_settlement.order_id'
            )
            ->join(
                'contract_companies',
                'contract_companies.id',
                '=',
                'orders.contract_company_id'
            )
            ->select(
                'settlements.date',
                'contract_companies.id as partner_id',
                'contract_companies.name as partner_name',
                DB::raw('COUNT(orders.id) as orders_count'),
                DB::raw('SUM(orders.contract_company_amount_base) as earnings')
            );
        // Settlement date filter
        if ($request->filled('from')) {
            $query->whereDate(
                'settlements.date',
                '>=',
                $request->from
            );
        }
        if ($request->filled('to')) {
            $query->whereDate(
                'settlements.date',
                '<=',
                $request->to
            );
        }
        // Multiple partners filter
        if ($request->filled('partners')) {
            $query->whereIn(
                'orders.contract_company_id',
                $request->partners
            );
        }
        $query->groupBy(
            'settlements.date',
            'contract_companies.id',
            'contract_companies.name'
        );
        // Sorting
        $sort = $request->get('sort', 'date');
        $direction = $request->get('direction', 'desc');
        switch ($sort) {
            case 'earnings':
                $query->orderBy(
                    'earnings',
                    $direction
                );
                break;
            case 'orders_count':
                $query->orderBy(
                    'orders_count',
                    $direction
                );
                break;
            case 'partner_name':
                $query->orderBy(
                    'partner_name',
                    $direction
                );
                break;
            default:
                $query->orderBy(
                    'settlements.date',
                    $direction
                );
        }
        $partnersReport = $query->get();
        // Cards
        $summary = [
            'partners_count' => $partnersReport
                ->pluck('partner_id')
                ->unique()
                ->count(),
            'orders_count' => $partnersReport
                ->sum('orders_count'),
            'earnings' => $partnersReport
                ->sum('earnings'),
        ];
        $partners = ContractCompany::orderBy('name')
            ->get();
        $currency = Currency::where('is_default', true)->select('symbol')->first();
        return view(
            'reports.partners',
            compact(
                'currency',
                'partnersReport',
                'partners',
                'summary'
            )
        );
    }
    public function partnerReportsPrint(Request $request)
    {
        $query = DB::table('settlements')
            ->join(
                'order_settlement',
                'order_settlement.settlement_id',
                '=',
                'settlements.id'
            )
            ->join(
                'orders',
                'orders.id',
                '=',
                'order_settlement.order_id'
            )
            ->join(
                'contract_companies',
                'contract_companies.id',
                '=',
                'orders.contract_company_id'
            )
            ->select(
                'settlements.date',
                'contract_companies.id as partner_id',
                'contract_companies.name as partner_name',
                DB::raw('COUNT(orders.id) as orders_count'),
                DB::raw('SUM(orders.contract_company_amount_base) as earnings')
            );
        // Settlement date filter
        if ($request->filled('from')) {
            $query->whereDate(
                'settlements.date',
                '>=',
                $request->from
            );
        }
        if ($request->filled('to')) {
            $query->whereDate(
                'settlements.date',
                '<=',
                $request->to
            );
        }
        // Multiple partners filter
        if ($request->filled('partners')) {
            $query->whereIn(
                'orders.contract_company_id',
                $request->partners
            );
        }
        $query->groupBy(
            'settlements.date',
            'contract_companies.id',
            'contract_companies.name'
        );
        // Sorting
        $sort = $request->get('sort', 'date');
        $direction = $request->get('direction', 'desc');
        switch ($sort) {
            case 'earnings':
                $query->orderBy(
                    'earnings',
                    $direction
                );
                break;
            case 'orders_count':
                $query->orderBy(
                    'orders_count',
                    $direction
                );
                break;
            case 'partner_name':
                $query->orderBy(
                    'partner_name',
                    $direction
                );
                break;
            default:
                $query->orderBy(
                    'settlements.date',
                    $direction
                );
        }
        $partnersReport = $query->get();
        // Cards
        $summary = [
            'partners_count' => $partnersReport
                ->pluck('partner_id')
                ->unique()
                ->count(),
            'orders_count' => $partnersReport
                ->sum('orders_count'),
            'earnings' => $partnersReport
                ->sum('earnings'),
        ];
        $currency = Currency::where('is_default', true)->select('symbol')->first();
        $companyInfo = CompanyInfo::first();
        return view(
            'reports.print.partners',
            compact(
                'currency',
                'companyInfo',
                'partnersReport',
                'summary'
            )
        );
    }
    public function companyReports(Request $request)
    {
        $query = Settlement::query();
        // Date filter
        if ($request->filled('from')) {
            $query->whereDate('date', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('date', '<=', $request->to);
        }
        $settlements = $query->get();
        $companyReport = $settlements
            ->groupBy('date')
            ->map(function ($items) {
                return (object)[
                    'date' => $items->first()->date,
                    'orders_count' => $items->sum('total_orders'),
                    'earnings' => $items->sum('company_total'),
                ];
            });
        // Sorting
        $sort = $request->get('sort', 'date');
        $direction = $request->get('direction', 'desc');
        $companyReport = $companyReport->sortBy(
            function ($item) use ($sort) {
                return match ($sort) {
                    'earnings' => $item->earnings,
                    'orders_count' => $item->orders_count,
                    default => $item->date,
                };
            },
            SORT_REGULAR,
            $direction === 'desc'
        );
        // Summary Cards
        $summary = [
            'days_count' => $companyReport->count(),
            'orders_count' => $companyReport->sum('orders_count'),
            'earnings' => $companyReport->sum('earnings'),
        ];
        $currency = Currency::where('is_default', true)->select('symbol')->first();
        return view(
            'reports.company',
            compact(
                'currency',
                'companyReport',
                'summary'
            )
        );
    }
    public function companyReportsPrint(Request $request)
    {
        $query = Settlement::query();
        // Date filter
        if ($request->filled('from')) {
            $query->whereDate('date', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('date', '<=', $request->to);
        }
        $settlements = $query->get();
        $companyReport = $settlements
            ->groupBy('date')
            ->map(function ($items) {
                return (object)[
                    'date' => $items->first()->date,
                    'orders_count' => $items->sum('total_orders'),
                    'earnings' => $items->sum('company_total'),
                ];
            });
        // Sorting
        $sort = $request->get('sort', 'date');
        $direction = $request->get('direction', 'desc');
        $companyReport = $companyReport->sortBy(
            function ($item) use ($sort) {
                return match ($sort) {
                    'earnings' => $item->earnings,
                    'orders_count' => $item->orders_count,
                    default => $item->date,
                };
            },
            SORT_REGULAR,
            $direction === 'desc'
        );
        // Summary Cards
        $summary = [
            'days_count' => $companyReport->count(),
            'orders_count' => $companyReport->sum('orders_count'),
            'earnings' => $companyReport->sum('earnings'),
        ];
        $currency = Currency::where('is_default', true)->select('symbol')->first();
        $companyInfo = CompanyInfo::first();
        return view(
            'reports.print.company',
            compact(
                'currency',
                'companyInfo',
                'companyReport',
                'summary'
            )
        );
    }
}
