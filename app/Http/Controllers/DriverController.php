<?php
namespace App\Http\Controllers;
use App\Helpers\ActivityLogger;
use App\Models\Currency;
use App\Models\Driver;
use App\Models\Settlement;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
class DriverController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:drivers.index', only: [
                'index',
            ]),
            new Middleware('permission:drivers.view', only: [
                'show',
            ]),
            new Middleware('permission:drivers.create', only: [
                'create',
                'store',
            ]),
            new Middleware('permission:drivers.edit', only: [
                'edit',
                'update',
            ]),
            new Middleware('permission:drivers.delete', only: [
                'destroy',
            ]),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = Driver::query()
            // Search
            ->when(request('search'), function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%'.request('search').'%')
                        ->orWhere('phone', 'like', '%'.request('search').'%')
                        ->orWhere('notes', 'like', '%'.request('search').'%');
                });
            })
            // Driver type
            ->when(request('type'), function ($query) {
                $query->where('driver_type', request('type'));
            })
            // Active status
            ->when(request()->filled('status'), function ($query) {
                $query->where('is_active', request('status'));
            })
            // Financial status
            ->when(request('financial_status'), function ($query) {
                switch (request('financial_status')) {
                    case 'pending_settlement':
                        $query->whereHas('settlements', function ($q) {
                            $q->where('status', 'pending');
                        });
                        break;
                    case 'pending_collection':
                        $query->whereHas('collections', function ($q) {
                            $q->where('status', 'pending');
                        });
                        break;
                    case 'settled':
                        $query
                            ->whereDoesntHave('settlements', function ($q) {
                                $q->where('status', 'pending');
                            })
                            ->whereDoesntHave('collections', function ($q) {
                                $q->where('status', 'pending');
                            });
                        break;
                    case 'pending':
                        $query->where(function ($q) {
                            $q->whereHas('settlements', function ($q) {
                                $q->where('status', 'pending');
                            })
                                ->orWhereHas('collections', function ($q) {
                                    $q->where('status', 'pending');
                                });
                        });
                        break;
                }
            });
        $drivers = $query
            // Pending settlements
            ->withSum([
                'settlements as pending_orders_count' => function ($q) {
                    $q->where('status', 'pending');
                },
            ], 'total_orders')
            ->withSum([
                'settlements as pending_settlement_amount' => function ($q) {
                    $q->where('status', 'pending');
                },
            ], 'company_total')
            // Pending collections
            ->withSum([
                'collections as pending_collection_amount' => function ($q) {
                    $q->where('status', 'pending');
                },
            ], 'driver_amount')
            ->paginate(10)
            ->withQueryString();
        $defaultCurrency = Currency::select('symbol')
            ->where('is_default', true)
            ->first();
        return view('drivers.index', compact(
            'drivers',
            'defaultCurrency'
        ));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $defaultCurrency = Currency::select('symbol')
            ->where('is_default', true)
            ->first();
        return view('drivers.create', compact('defaultCurrency'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:drivers,name',
            'phone' => 'nullable|string|max:255',
            'driver_type' => 'required|in:percentage,fixed,custom',
            'percentage' => 'nullable|required_if:driver_type,percentage,custom|numeric|min:0|max:100',
            'salary' => 'nullable|required_if:driver_type,fixed,custom|numeric|min:0',
            'notes' => 'nullable|string',
        ]);
        $driver = Driver::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
            'driver_type' => $validated['driver_type'],
            'salary' => in_array($validated['driver_type'], ['fixed', 'custom'])
                ? $validated['salary']
                : null,
            'driver_percentage' => in_array($validated['driver_type'], ['percentage', 'custom'])
                ? $validated['percentage']
                : 0,
            'notes' => $validated['notes'] ?? null,
            'is_active' => true,
        ]);
        ActivityLogger::log(
            'created',
            $driver,
            "Created driver {$driver->name}",
            [],
            $driver->fresh()->toArray()
        );
        return redirect()
            ->route('drivers.index')
            ->with('success', __('messages.created_successfully', [
                'item' => __('messages.driver'),
            ]));
    }
    /**
     * Display the specified resource.
     */
    public function show(Driver $driver)
    {
        $pendingSettlements = Settlement::where('driver_id', $driver->id)
            ->where('status', 'pending')
            ->get();
        $defaultCurrency = Currency::select('symbol')
            ->where('is_default', true)
            ->first();
        return view('drivers.view', compact('driver', 'pendingSettlements', 'defaultCurrency'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Driver $driver)
    {
        $defaultCurrency = Currency::select('symbol')
            ->where('is_default', true)
            ->first();
        return view('drivers.edit', compact('defaultCurrency', 'driver'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Driver $driver)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:drivers,name,'.$driver->id,
            'phone' => 'nullable|string|max:255',
            'driver_type' => 'required|in:percentage,fixed,custom',
            'percentage' => 'nullable|numeric|min:0|max:100',
            'salary' => 'nullable|numeric|min:0',
            'percentage' => 'nullable|required_if:driver_type,percentage,custom|numeric|min:0|max:100',
            'salary' => 'nullable|required_if:driver_type,fixed,custom|numeric|min:0',
            'notes' => 'nullable|string',
            'is_active' => 'required|boolean',
        ]);
        $old = $driver->toArray();
        $driver->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'] ?? null,
            'driver_type' => $validated['driver_type'],
            'salary' => in_array($validated['driver_type'], ['fixed', 'custom'])
                ? $validated['salary']
                : null,
            'driver_percentage' => in_array($validated['driver_type'], ['percentage', 'custom'])
                ? $validated['percentage']
                : 0,
            'notes' => $validated['notes'] ?? null,
            'is_active' => $validated['is_active'],
        ]);
        ActivityLogger::log(
            'updated',
            $driver,
            "Updated driver {$driver->name}",
            $old,
            $driver->fresh()->toArray()
        );
        return redirect()
            ->route('drivers.index')
            ->with('success', __('messages.updated_successfully', [
                'item' => __('messages.driver'),
            ]));
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Driver $driver)
    {
        if ($driver->orders()->exists()) {
            return redirect()
                ->route('drivers.index')
                ->with('error', __('messages.cannot_delete_item_orders', [
                    'item' => __('messages.driver'),
                ]));
        }
        if ($driver->settlements()->exists()) {
            return redirect()
                ->route('drivers.index')
                ->with('error', __('messages.cannot_delete_item_settlements', [
                    'item' => __('messages.driver'),
                ]));
        }
        if ($driver->collections()->exists()) {
            return redirect()
                ->route('drivers.index')
                ->with('error', __('messages.cannot_delete_item_collections', [
                    'item' => __('messages.driver'),
                ]));
        }
        $old = $driver->toArray();
        ActivityLogger::log(
            'deleted',
            $driver,
            "Deleted driver {$driver->name}",
            $old,
            []
        );
        $driver->delete();
        return redirect()
            ->route('drivers.index')
            ->with('success', __('messages.deleted_successfully', [
                'item' => __('messages.driver'),
            ]));
    }
}
