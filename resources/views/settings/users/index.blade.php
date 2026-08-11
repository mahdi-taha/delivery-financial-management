<x-main page="Settings: Users">
    <x-slot name="header">
        <x-header />
    </x-slot>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1">{{ __('common.users') }}</h4>
                    <p class="text-muted mb-0">{{ __('common.manage_your_users') }}</p>
                </div>
                <a href="{{ route('users.create') }}" class="btn btn-main">
                    <i class="bi bi-person-plus"></i>
                    {{ __('common.add_user') }}
                </a>
            </div>
        </div>
    </div>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle text-nowrap">
                    <thead class="table-head">
                        <tr>
                            <th>#</th>
                            <th>{{ __('common.name') }}</th>
                            <th>{{ __('common.username') }}</th>
                            <th>{{ __('common.role') }}</th>
                            <th>{{ __('common.salary') }}</th>
                            <th>{{ __('common.status') }}</th>
                            <th width="80">{{ __('common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr class="table-row">
                                <td>
                                    {{ $loop->iteration }}
                                </td>
                                <td>
                                    {{ $user->name }}
                                    @if ($user->is_system)
                                        <span class="badge bg-success">
                                            {{ __('common.system') }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    {{ $user->username }}
                                </td>
                                <td>
                                    @if ($user->role)
                                        <span class="badge bg-info text-dark">
                                            {{ $user->role->name }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary">
                                            {{ __('common.no_role') }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    {{ number_format($user->salary ?? 0, 2) }}
                                    {{ $defaultCurrency->symbol }}
                                </td>
                                <td>
                                    @if ($user->is_active)
                                        <span class="badge bg-success">
                                            {{ __('common.active') }}
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            {{ __('common.inactive') }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <a href="{{ route('users.edit', $user) }}"
                                            class="btn btn-sm btn-success d-inline-flex align-items-center">
                                            <i class="bi bi-pencil-square me-1"></i>
                                            <span>{{ __('common.edit') }}</span>
                                        </a>
                                        <form action="{{ route('users.destroy', $user) }}" method="POST"
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
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-person-badge fs-2 d-block mb-2"></i>
                                        {{ __('common.no_users_found') }}
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