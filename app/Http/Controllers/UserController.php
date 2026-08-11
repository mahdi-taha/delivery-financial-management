<?php
namespace App\Http\Controllers;
use App\Helpers\ActivityLogger;
use App\Models\Currency;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
class UserController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:users.index', only: [
                'index',
            ]),
            new Middleware('permission:users.create', only: [
                'create',
                'store'
            ]),
            new Middleware('permission:users.edit', only: [
                'edit',
                'update'
            ]),
            new Middleware('permission:users.delete', only: [
                'destroy'
            ]),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('role')->get();
        $defaultCurrency = Currency::where('is_default', true)->first();
        return view('settings.users.index', compact('users', 'defaultCurrency'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        $defaultCurrency = Currency::where('is_default', true)->first();
        return view('settings.users.create', compact('roles', 'defaultCurrency'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'password' => 'required|min:6',
            'salary' => 'nullable|min:0|numeric',
            'role' => 'required|exists:roles,id',
        ]);
        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'salary' => $validated['salary'],
            'password' => Hash::make($validated['password']),
            'role_id' => $validated['role'],
            'is_active' => true,
            'is_system' => false,
        ]);
        ActivityLogger::log(
            'created',
            $user,
            "Created user {$user->name}",
            [],
            $user->fresh()->toArray()
        );
        return redirect()
            ->route('users.index')
            ->with('success', __('messages.created_successfully', [
                'item' => __('messages.user')
            ]));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        if ($user->is_system) {
            return back()->with(
                'error',
                __('messages.cant_edit', [
                    'item' => __('messages.system_user')
                ])
            );
        }
        $roles = Role::all();
        $defaultCurrency = Currency::where('is_default', true)->first();
        return view('settings.users.edit', compact(
            'user',
            'roles',
            'defaultCurrency'
        ));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        if ($user->is_system) {
            return back()->with(
                'error',
                __('messages.cant_edit', [
                    'item' => __('messages.system_user')
                ])
            );
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'password' => 'nullable|min:6',
            'role' => 'required|exists:roles,id',
            'salary' => 'nullable|min:0|numeric',
            'is_active' => 'boolean',
        ]);
        $old = $user->toArray();
        $user->update([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'role_id' => $validated['role'],
            'salary' => $validated['salary'],
            'is_active' => $validated['is_active'],
            'password' => $request->filled('password')
                ? Hash::make($request->password)
                : $user->password,
        ]);
        ActivityLogger::log(
            'updated',
            $user,
            "Updated user {$user->name}",
            [],
            $user->fresh()->toArray()
        );
        return redirect()
            ->route('users.index')
            ->with('success',  __('messages.updated_successfully', [
                'item' => __('messages.user')
            ]));
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($user->is_system) {
            return back()->with(
                'error',
                __('messages.cant_delete', [
                    'item' => __('messages.system_user')
                ])
            );
        }
        if ($user->id === auth()->id()) {
            return back()->with(
                'error',
            __('messages.cannot_delete_own_account'));
        }
        $old = $user->toArray();
        ActivityLogger::log(
            'deleted',
            $user,
            "Deleted user {$user->name}",
            $old,
            []
        );
        $user->delete();
        return back()->with(
            'success',
            __('messages.deleted_successfully', [
                'item' => __('messages.user')
            ])
        );
    }
}
