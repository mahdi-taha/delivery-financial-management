<x-main page="Settings: Activity Logs">
    <x-slot name="header">
        <x-header />
    </x-slot>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1">{{ __('activity_logs.title') }}</h4>
                    <p class="text-muted mb-0">{{ __('activity_logs.subtitle') }}</p>
                </div>
            </div>
        </div>
    </div>
    <form method="GET" class="card card-body mb-4">
        <div class="row g-3">
            <div class="col-md-6 col-lg-2">
                <input type="text" name="search" class="form-control"
                    placeholder="{{ __('activity_logs.search') }}..." value="{{ request('search') }}">
            </div>
            <div class="col-12 col-md-6 col-lg-2">
                <select name="action" class="form-select">
                    <option value="">
                        {{ __('activity_logs.all_actions') }}
                    </option>
                    @foreach (['created', 'updated', 'deleted', 'login', 'logout', 'uploaded', 'canceled', 'payment', 'Closed'] as $action)
                        <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                            {{ __('activity_logs.actions_list.' . $action) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-6 col-lg-2">
                <select name="model" class="form-select">
                    <option value="">
                        {{ __('activity_logs.all_modules') }}
                    </option>
                    @foreach ([
        'App\Models\User' => 'users',
        'App\Models\Driver' => 'drivers',
        'App\Models\Collection' => 'collections',
        'App\Models\CompanyInfo' => 'company_info',
        'App\Models\ContractCompany' => 'partners',
        'App\Models\Currency' => 'currencies',
        'App\Models\FinancialTransaction' => 'transactions',
        'App\Models\Order' => 'orders',
        'App\Models\PaymentMethod' => 'payment_methods',
        'App\Models\Permission' => 'permissions',
        'App\Models\Settlement' => 'settlements',
        'App\Models\Role' => 'roles',
    ] as $key => $label)
                        <option value="{{ $key }}">
                            {{ __('activity_logs.modules.' . $label) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-12 col-md-6 col-lg-2">
                <input type="date" name="from" class="form-control" value="{{ request('from') }}">
            </div>
            <div class="col-12 col-md-6 col-lg-2">
                <input type="date" name="to" class="form-control" value="{{ request('to') }}">
            </div>
            <div class="col-12 col-md-6 col-lg-2 d-flex gap-2">
                <button class="btn btn-main w-100">
                    <i class="bi bi-funnel me-1"></i>
                    {{ __('activity_logs.filter') }}
                </button>
                <a href="{{ route('activity-logs.index') }}" class="btn border btn-light w-100">
                    <i class="bi bi-arrow-counterclockwise"></i>
                    {{ __('activity_logs.reset') }}
                </a>
            </div>
        </div>
    </form>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle text-nowrap">
                    <thead class="table-head">
                        <tr>
                            <th>{{ __('activity_logs.user') }}</th>
                            <th>{{ __('activity_logs.action') }}</th>
                            <th>{{ __('activity_logs.description') }}</th>
                            <th>{{ __('activity_logs.date') }}</th>
                            <th width="80">{{ __('activity_logs.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                            <tr class="table-row">
                                <td>
                                    {{ $log->user?->name ?? 'System' }}
                                </td>
                                <td>
                                    @php
                                        $badge = match ($log->action) {
                                            'created' => 'success',
                                            'updated' => 'warning',
                                            'deleted' => 'danger',
                                            'login' => 'primary',
                                            'logout' => 'secondary',
                                            default => 'dark',
                                        };
                                    @endphp
                                    <span class="badge bg-{{ $badge }}">
                                          {{ __('activity_logs.actions_list.' . $log->action) }}
                                    </span>
                                </td>
                                <td>
                                    {{ $log->description }}
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($log->created_at)->format('d-m-Y H:i') }}
                                </td>
                                {{-- actions --}}
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <a href="{{ route('activity-logs.show', $log->id) }}"
                                            class="btn btn-sm btn-secondary d-inline-flex align-items-center">
                                            <i class="bi bi-eye me-1"></i>
                                            <span>{{ __('activity_logs.view') }}</span>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi  bi-clock-history fs-2 d-block mb-2"></i>
                                        {{ __('activity_logs.no_logs') }}
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="mt-3">
        {{ $logs->links() }}
    </div>
</x-main>
