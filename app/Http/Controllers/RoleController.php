<?php
namespace App\Http\Controllers;
use App\Helpers\ActivityLogger;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
class RoleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:roles.index', only: [
                'index',
            ]),
            new Middleware('permission:roles.create', only: [
                'create',
                'store'
            ]),
            new Middleware('permission:roles.edit', only: [
                'edit',
                'update'
            ]),
            new Middleware('permission:roles.delete', only: [
                'destroy'
            ]),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::withCount('users')
            ->with('permissions')
            ->get();
        return view('settings.roles.index', compact('roles'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('settings.roles.create');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ]);
        $role = Role::create([
            'name' => $validated['name'],
            'is_system' => false,
        ]);
        ActivityLogger::log(
            'created',
            $role,
            "Created role {$role->name}",
            [],
            $role->fresh()->toArray()
        );
        return redirect()
            ->route('roles.index')
            ->with('success',  __('messages.created_successfully', [
                'item' => __('messages.role')
            ]));
    }
    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        //
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        if ($role->is_system) {
            return back()->with(
                'error',
                __('messages.cant_edit', [
                    'item' => __('messages.system_role')
                ])
            );
        }
        $permissions = Permission::orderBy('group')
            ->orderBy('name')
            ->get()
            ->groupBy('group');
        $role->load('permissions');
        return view('settings.roles.edit', compact(
            'role',
            'permissions'
        ));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        if ($role->is_system) {
            return back()->with(
                'error',
                __('messages.cant_edit', [
                    'item' => __('messages.system_role')
                ])
            );
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);
        $role->update([
            'name' => $validated['name'],
        ]);
        $role->permissions()->sync(
            $validated['permissions'] ?? []
        );
        ActivityLogger::log(
            'updated',
            $role,
            "Updated role {$role->name}",
            [],
            $role->fresh()->toArray()
        );
        return redirect()
            ->route('roles.index')
            ->with('success', __('messages.updated_successfully', [
                'item' => __('messages.role')
            ]));
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        if ($role->is_system) {
            return back()->with(
                'error',
                __('messages.cant_delete', [
                    'item' => __('messages.system_role')
                ])
            );
        }
        if ($role->users()->exists()) {
            return back()->with(
                      'error',
                __('messages.cannot_delete_item_users', [
                    'item' => __('messages.system_role')
                ])
            );
        }
        $role->permissions()->detach();
        $old = $role->toArray();
        ActivityLogger::log(
            'deleted',
            $role,
            "Deleted role {$role->name}",
            $old,
            []
        );
        $role->delete();
        return redirect()
            ->route('roles.index')
            ->with(
                'success', __('messages.deleted_successfully', [
                    'item' => __('messages.role')
                ])
            );
    }
}
