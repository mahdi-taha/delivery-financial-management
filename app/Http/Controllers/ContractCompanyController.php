<?php
namespace App\Http\Controllers;
use App\Helpers\ActivityLogger;
use App\Models\ContractCompany;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
class ContractCompanyController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:partners.index', only: [
                'index',
            ]),
            new Middleware('permission:partners.create', only: [
                'create',
                'store'
            ]),
            new Middleware('permission:partners.edit', only: [
                'edit',
                'update'
            ]),
            new Middleware('permission:partners.delete', only: [
                'destroy'
            ]),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = ContractCompany::query()
            // Search
            ->when(request('search'), function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . request('search') . '%')
                        ->orWhere('phone', 'like', '%' . request('search') . '%');
                });
            })
            // partners type
            ->when(request('type'), function ($query) {
                $query->where('fee_type', request('type'));
            })
            // Active status
            ->when(request()->filled('status'), function ($query) {
                $query->where('is_active', request('status'));
            });
        $partners = $query
            ->paginate(10)
            ->withQueryString();
        $defaultCurrency = Currency::select('symbol')
            ->where('is_default', true)
            ->first();
        return view('partners.index', compact('partners', 'defaultCurrency'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $defaultCurrency = Currency::select('symbol')
            ->where('is_default', true)
            ->first();
        return view('partners.create', compact('defaultCurrency'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:contract_companies,name',
            'phone' => 'nullable|string|max:255',
            'fee_type' => 'required|in:fixed,percentage',
            'percentage' => 'required_if:fee_type,percentage|nullable|numeric|min:0|max:100',
            'fixed_fee' => 'required_if:fee_type,fixed|nullable|numeric|min:0',
        ]);
        $partner =  ContractCompany::create([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'fee_type' => $validated['fee_type'],
            'percentage' => in_array($validated['fee_type'], ['percentage']) ? $validated['percentage'] : null,
            'fixed_fee' => in_array($validated['fee_type'], ['fixed']) ? $validated['fixed_fee'] : null,
            'is_active' => true,
        ]);
        ActivityLogger::log(
            'created',
            $partner,
            "Created partner {$partner->name}",
            [],
            $partner->fresh()->toArray()
        );
        return redirect()
            ->route('partners.index')
            ->with('success', __('messages.created_successfully', [
                'item' => __('messages.partner')
            ]));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ContractCompany $partner)
    {
        $defaultCurrency = Currency::select('symbol')
            ->where('is_default', true)
            ->first();
        return view('partners.edit', compact('defaultCurrency', 'partner'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ContractCompany $partner)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:contract_companies,name,' . $partner->id,
            'phone' => 'nullable|string|max:255',
            'fee_type' => 'required|in:fixed,percentage',
            'percentage' => 'required_if:fee_type,percentage|nullable|numeric|min:0|max:100',
            'fixed_fee' => 'required_if:fee_type,fixed|nullable|numeric|min:0',
            'is_active' => 'required|boolean',
        ]);
        $old = $partner->toArray();
        $partner->update([
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'fee_type' => $validated['fee_type'],
            'percentage' => in_array($validated['fee_type'], ['percentage']) ? $validated['percentage'] : null,
            'fixed_fee' => in_array($validated['fee_type'], ['fixed']) ? $validated['fixed_fee'] : null,
            'is_active' => $validated['is_active'],
        ]);
        ActivityLogger::log(
            'updated',
            $partner,
            "Updated partner {$partner->name}",
            $old,
            $partner->fresh()->toArray()
        );
        return redirect()
            ->route('partners.index')
            ->with('success', __('messages.updated_successfully', [
                'item' => __('messages.partner')
            ]));
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContractCompany $partner)
    {
        if ($partner->orders()->exists()) {
            return redirect()
                ->route('partners.index')
                ->with('error', __('messages.cannot_delete_item_orders', [
                    'item' => __('messages.partner')
                ]));
        }
        if ($partner->transactions()->exists()) {
            return redirect()
                ->route('partners.index')
                ->with('error', __('messages.cannot_delete_item_transactions', [
                    'item' => __('messages.partner')
                ]));
        }
        $old = $partner->toArray();
        ActivityLogger::log(
            'deleted',
            $partner,
            "Deleted partner {$partner->name}",
            $old,
            []
        );
        $partner->delete();
        return redirect()
            ->route('partners.index')
            ->with('success', __('messages.deleted_successfully', [
                'item' => __('messages.partner')
            ]));
    }
}
