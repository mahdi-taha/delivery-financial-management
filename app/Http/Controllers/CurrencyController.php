<?php
namespace App\Http\Controllers;
use App\Helpers\ActivityLogger;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
class CurrencyController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:currencies.index', only: [
                'index',
            ]),
            new Middleware('permission:currencies.create', only: [
                'create',
                'store'
            ]),
            new Middleware('permission:currencies.edit', only: [
                'edit',
                'update'
            ]),
            new Middleware('permission:currencies.delete', only: [
                'destroy'
            ]),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currencies = Currency::all();
        return view('settings.currencies.index', compact('currencies'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('settings.currencies.create');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:currencies,name',
            'symbol' => 'required|string|max:10|unique:currencies,symbol',
            'rate' => 'required|numeric|min:0',
            'input_mode' => 'required|in:normal,tens,hundreds,thousands,millions',
            'rounding_unit' => 'nullable|numeric|min:0',
        ]);
        $currency = Currency::create([
            'name' => $validated['name'],
            'symbol' => $validated['symbol'],
            'rate' => $validated['rate'],
            'input_mode' => $validated['input_mode'],
            'rounding_unit' => $validated['rounding_unit'] ?? 0,
            'is_default' => false,
        ]);
        ActivityLogger::log(
            'created',
            $currency,
            "Created currency {$currency->name}",
            [],
            $currency->fresh()->toArray()
        );
        return redirect()->route('currencies.index')->with('success', __('messages.created_successfully', [
            'item' => __('messages.currency')
        ]));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Currency $currency)
    {
        return view('settings.currencies.edit', compact('currency'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Currency $currency)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:currencies,name,' . $currency->id,
            'symbol' => 'required|string|max:10|unique:currencies,symbol,' . $currency->id,
            'rate' => 'required|numeric|min:0',
            'input_mode' => 'required|in:normal,tens,hundreds,thousands,millions',
            'rounding_unit' => 'nullable|numeric|min:0',
        ]);
        $old = $currency->toArray();
        $currency->update([
            'name' => $validated['name'],
            'symbol' => $validated['symbol'],
            'rate' => $currency->is_default ? 1 : $validated['rate'],
            'input_mode' => $validated['input_mode'],
            'rounding_unit' => $validated['rounding_unit'] ?? 0,
        ]);
        ActivityLogger::log(
            'updated',
            $currency,
            "Updated currency {$currency->name}",
            $old,
            $currency->fresh()->toArray()
        );
        return redirect()->route('currencies.index')->with('success',  __('messages.updated_successfully', [
            'item' => __('messages.currency')
        ]));
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Currency $currency)
    {
        if ($currency->is_default) {
            return redirect()->route('currencies.index')->with('error',  __('messages.cant_delete', [
                    'item' => __('messages.system_currency')
                ]));
        }
        if ($currency->orders()->exists()) {
            return redirect()->route('currencies.index')->with('error',  __('messages.cannot_delete_item_orders', [
                    'item' => __('messages.currency')
                ]));
        }
        if ($currency->transactions()->exists()) {
            return redirect()->route('currencies.index')->with('error',  __('messages.cannot_delete_item_transactions', [
                    'item' => __('messages.currency')
                ]));
        }
        $old = $currency->toArray();
        ActivityLogger::log(
            'deleted',
            $currency,
            "Deleted currency {$currency->name}",
            $old,
            []
        );
        $currency->delete();
        return redirect()->route('currencies.index')->with('success',  __('messages.deleted_successfully', [
                    'item' => __('messages.currency')
                ]));
    }
}
