<x-main page="Settings: Roles">
    <x-slot name="header">
        <x-header />
    </x-slot>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1">{{ __('common.roles') }}</h4>
                    <p class="text-muted mb-0">{{ __('common.manage_user_roles') }}</p>
                </div>
                <a href="{{ route('roles.create') }}" class="btn btn-main">
                    <i class="bi bi-plus-square"></i>
                    {{ __('common.add_role') }}
                </a>
            </div>
        </div>
    </div>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-head">
                        <tr>
                            <th>{{ __('common.role') }}</th>
                            <th>{{ __('common.users') }}</th>
                            <th>{{ __('common.permissions') }}</th>
                            <th width="80">{{ __('common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($roles as $role)
                            <tr class="table-row">
                                {{-- Role --}}
                                <td>
                                    {{ $role->name }}
                                    @if ($role->is_system)
                                        <span class="badge bg-success">
                                            {{ __('common.system') }}
                                        </span>
                                    @endif
                                </td>
                                {{-- Users Count --}}
                                <td>
                                    {{ $role->users_count }}
                                </td>
                                {{-- Permissions --}}
                                <td>
                                    @if ($role->permissions->count())
                                        <span class="badge bg-secondary">
                                            {{ $role->permissions->count() }}
                                            {{ __('common.permissions') }}
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-dark">
                                            {{ __('common.no_permissions') }}
                                        </span>
                                    @endif
                                </td>
                                {{-- Actions --}}
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <a href="{{ route('roles.edit', $role) }}"
                                            class="btn btn-sm btn-success d-inline-flex align-items-center">
                                            <i class="bi bi-pencil-square me-1"></i>
                                            <span>{{ __('common.edit') }}</span>
                                        </a>
                                        <form action="{{ route('roles.destroy', $role) }}" method="POST"
                                            class="delete-form d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn btn-sm btn-danger d-inline-flex align-items-center">
                                                <i class="bi bi-trash me-1"></i>
                                                <span>{{ __('common.delete') }}</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-shield-check fs-2 d-block mb-2"></i>
                                        {{ __('common.no_roles_found') }}
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-main>