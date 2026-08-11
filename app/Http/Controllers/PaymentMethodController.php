<?php
namespace App\Http\Controllers;
use App\Helpers\ActivityLogger;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
class PaymentMethodController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:payment_methods.index', only: [
                'index',
            ]),
            new Middleware('permission:payment_methods.create', only: [
                'create',
                'store'
            ]),
            new Middleware('permission:payment_methods.edit', only: [
                'edit',
                'update'
            ]),
            new Middleware('permission:payment_methods.delete', only: [
                'destroy'
            ]),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payment_methods = PaymentMethod::all();
        return view('settings.payment_methods.index', compact('payment_methods'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('settings.payment_methods.create');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:payment_methods,name',
        ]);
        $paymentMethod = PaymentMethod::create([
            'name' => $validated['name'],
            'is_active' => true,
        ]);
        ActivityLogger::log(
            'created',
            $paymentMethod,
            "Created payment method {$paymentMethod->name}",
            [],
            $paymentMethod->fresh()->toArray()
        );
        return redirect()->route('payment_methods.index')->with('success',  __('messages.created_successfully', [
            'item' => __('messages.payment_method')
        ]));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PaymentMethod $paymentMethod)
    {
        if ($paymentMethod->is_default) {
            return redirect()->route('payment_methods.index')->with('error', __('messages.cant_edit', [
                'item' => __('messages.system_payment_method')
            ]));
        }
        return view('settings.payment_methods.edit', compact('paymentMethod'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        if ($paymentMethod->is_default) {
            return redirect()->route('payment_methods.index')->with('error', __('messages.cant_edit', [
                'item' => __('messages.system_payment_method')
            ]));
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:payment_methods,name,' . $paymentMethod->id,
            'is_active' => 'boolean',
        ]);
        $old = $paymentMethod->toArray();
        $paymentMethod->update($validated);
        ActivityLogger::log(
            'updated',
            $paymentMethod,
            "Updated payment method {$paymentMethod->name}",
            $old,
            $paymentMethod->fresh()->toArray()
        );
        return redirect()->route('payment_methods.index')->with('success', __('messages.updated_successfully', [
            'item' => __('messages.payment_method')
        ]));
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        if ($paymentMethod->is_default) {
            return redirect()->route('payment_methods.index')->with('error', __('messages.cant_delete', [
                'item' => __('messages.system_payment_method')
            ]));
        }
        if ($paymentMethod->collections()->exists()) {
            return redirect()->route('payment_methods.index')->with('error', __('messages.cannot_delete_item_collections', [
                'item' => __('messages.payment_method')
            ]));
        }
        if ($paymentMethod->transactions()->exists()) {
            return redirect()->route('payment_methods.index')->with('error', __('messages.cannot_delete_item_transactions', [
                'item' => __('messages.payment_method')
            ]));
        }
        $old = $paymentMethod->toArray();
        ActivityLogger::log(
            'deleted',
            $paymentMethod,
            "Deleted payment method {$paymentMethod->name}",
            $old,
            []
        );
        $paymentMethod->delete();
        return redirect()->route('payment_methods.index')->with('success',  __('messages.deleted_successfully', [
                'item' => __('messages.payment_method')
            ]));
    }
}
