<x-main page="Drivers">
    <x-slot name="header">
        <x-header />
    </x-slot>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1">{{ __('common.drivers') }}</h4>
                    <p class="text-muted mb-0">{{ __('drivers.manage_your_drivers') }}</p>
                </div>
                <a href="{{ route('drivers.create') }}" class="btn btn-main">
                    <i class="bi bi-plus-square"></i>
                    {{ __('drivers.add_driver') }}
                </a>
            </div>
            <hr class="thick-hr">
            <form method="GET" action="{{ route('drivers.index') }}" class="row g-3">
                <div class="col-md-6 col-lg-2">
                    <input type="text" name="search" class="form-control"
                        placeholder="{{ __('common.search') }}..." value="{{ request('search') }}">
                </div>
                <div class="col-12 col-md-6 col-lg-2">
                    <select name="type" class="form-select">
                        <option value="">
                            {{ __('common.all_types') }}
                        </option>
                        <option value="custom" {{ request('type') == 'custom' ? 'selected' : '' }}>
                            {{ __('drivers.custom') }}
                        </option>
                        <option value="fixed" {{ request('type') == 'fixed' ? 'selected' : '' }}>
                            {{ __('drivers.fixed') }}
                        </option>
                        <option value="percentage" {{ request('type') == 'percentage' ? 'selected' : '' }}>
                            {{ __('drivers.percentage') }}
                        </option>
                    </select>
                </div>
                <div class="col-md-4 col-lg-2">
                    <select name="status" class="form-select">
                        <option value="">{{ __('common.all_status') }}</option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>
                            {{ __('common.active') }}</option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>
                            {{ __('common.inactive') }}</option>
                    </select>
                </div>
                <div class="col-md-4 col-lg-2">
                    <select name="financial_status" class="form-select">
                        <option value="">
                            {{ __('drivers.all_financial_status') }}
                        </option>
                        <option value="pending_settlement"
                            {{ request('financial_status') == 'pending_settlement' ? 'selected' : '' }}>
                            {{ __('drivers.pending_settlement') }}
                        </option>
                        <option value="pending_collection"
                            {{ request('financial_status') == 'pending_collection' ? 'selected' : '' }}>
                            {{ __('drivers.pending_collection') }}
                        </option>
                        <option value="settled" {{ request('financial_status') == 'settled' ? 'selected' : '' }}>
                            {{ __('drivers.all_settled') }}
                        </option>
                        <option value="pending" {{ request('financial_status') == 'pending' ? 'selected' : '' }}>
                            {{ __('drivers.all_pending') }}
                        </option>
                    </select>
                </div>
                <div class="col-md-4 col-lg-4 d-flex gap-2">
                    <button class="btn btn-main w-100">
                        <i class="bi bi-funnel me-1"></i>
                        {{ __('common.filter') }}
                    </button>
                    <a href="{{ route('drivers.index') }}" class="btn border btn-light w-100">
                        <i class="bi bi-arrow-counterclockwise"></i>
                        {{ __('common.reset') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle text-nowrap">
                    <thead class="table-head">
                        <tr>
                            <th>{{ __('common.driver') }}</th>
                            <th>{{ __('common.phone') }}</th>
                            <th>{{ __('common.type') }}</th>
                            <th>{{ __('common.percentage') }}</th>
                            <th>{{ __('common.salary') }}</th>
                            <th>{{ __('common.orders') }}</th>
                            <th>{{ __('common.payment') }}</th>
                            <th>{{ __('common.collections') }}</th>
                            <th>{{ __('common.status') }}</th>
                            <th width="80">{{ __('common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($drivers as $driver)
                            <tr class="table-row">
                                <td>
                                    <div class="fw-semibold">
                                        {{ $driver->name ?? 'N/A' }}
                                    </div>
                                    @if ($driver->notes)
                                        <small class="text-muted">
                                            {{ $driver->notes }}
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    {{ $driver->phone ?? '---' }}
                                </td>
                                <td>
                                    {{ __('drivers.' . $driver->driver_type) }}
                                </td>
                                <td>
                                    {{ $driver->driver_percentage ?? 0 }}%
                                </td>
                                <td>
                                    {{ number_format($driver->salary ?? 0, 2) }} {{ $defaultCurrency->symbol }}
                                </td>
                                <td>
                                    {{ $driver->pending_orders_count ?? 0 }}
                                </td>
                                <td>
                                    {{ number_format($driver->pending_settlement_amount ?? 0, 2) }}
                                    {{ $defaultCurrency->symbol }}
                                </td>
                                <td>
                                    {{ number_format($driver->pending_collection_amount ?? 0, 2) }}
                                    {{ $defaultCurrency->symbol }}
                                </td>
                                <td>
                                    @if ($driver->is_active)
                                        <span class="badge bg-success">{{ __('common.active') }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ __('common.inactive') }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <a href="{{ route('drivers.show', $driver->id) }}"
                                            class="btn btn-sm btn-secondary d-inline-flex align-items-center">
                                            <i class="bi bi-eye me-1"></i>
                                            <span class="me-1"> {{ __('common.view') }}</span>
                                        </a>
                                        <a href="{{ route('drivers.edit', $driver->id) }}?back_url={{ urlencode(url()->current()) }}"
                                            class="btn btn-sm btn-success d-inline-flex align-items-center">
                                            <i class="bi bi-pencil-square me-1"></i>
                                            <span class="me-1"> {{ __('common.edit') }}</span>
                                        </a>
                                        <form action="{{ route('drivers.destroy', $driver->id) }}" method="POST"
                                            class="delete-form d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn btn-sm btn-danger d-inline-flex align-items-center">
                                                <i class="bi bi-trash me-1"></i>
                                                <span class="me-1">{{ __('common.delete') }}</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">
                                    <i class="bi bi-car-front-fill fs-2 d-block mb-2"></i>
                                    {{ __('drivers.no_drivers_found') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-3">
                    {{ $drivers->links() }}
                </div>
            </div>
        </div>
    </div>
</x-main>
