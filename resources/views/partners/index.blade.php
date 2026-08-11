<x-main page="Partners">
    <x-slot name="header">
        <x-header />
    </x-slot>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1">{{ __('partners.partners') }}</h4>
                    <p class="text-muted mb-0">
                        {{ __('partners.manage_your_partners') }}
                    </p>
                </div>
                <a href="{{ route('partners.create') }}" class="btn btn-main">
                    <i class="bi bi-plus-square"></i>
                    {{ __('partners.add_partner') }}
                </a>
            </div>
            <hr class="thick-hr">
            <form method="GET" action="{{ route('partners.index') }}" class="row g-3">
                <div class="col-md-6 col-lg-4">
                    <input type="text"
                        name="search"
                        class="form-control"
                        placeholder="{{ __('common.search') }}..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-12 col-md-6 col-lg-2">
                    <select name="type" class="form-select">
                        <option value="">
                            {{ __('common.all_types') }}
                        </option>
                        <option value="fixed" {{ request('type') == 'fixed' ? 'selected' : '' }}>
                            {{ __('common.fixed') }}
                        </option>
                        <option value="percentage" {{ request('type') == 'percentage' ? 'selected' : '' }}>
                            {{ __('common.percentage') }}
                        </option>
                    </select>
                </div>
                <div class="col-md-3 col-lg-2">
                    <select name="status" class="form-select">
                        <option value="">
                            {{ __('common.all_status') }}
                        </option>
                        <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>
                            {{ __('common.active') }}
                        </option>
                        <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>
                            {{ __('common.inactive') }}
                        </option>
                    </select>
                </div>
                <div class="col-md-3 col-lg-4 d-flex gap-2">
                    <button class="btn btn-main w-100">
                        <i class="bi bi-funnel me-1"></i>
                        {{ __('common.filter') }}
                    </button>
                    <a href="{{ route('partners.index') }}"
                        class="btn border btn-light w-100">
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
                            <th>{{ __('common.name') }}</th>
                            <th>{{ __('partners.fee_type') }}</th>
                            <th>{{ __('partners.fixed_fee') }}</th>
                            <th>{{ __('partners.percentage_fee') }}</th>
                            <th>{{ __('common.phone') }}</th>
                            <th>{{ __('common.status') }}</th>
                            <th>
                                {{ __('common.actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($partners as $partner)
                            <tr class="table-row">
                                <td>{{ $partner->name }}</td>
                                <td>
                                    {{ __('common.' . $partner->fee_type) }}
                                </td>
                                <td>
                                    {{ number_format($partner->fixed_fee ?? 0, 2) }}
                                    {{ $defaultCurrency->symbol }}
                                </td>
                                <td>
                                    {{ $partner->percentage ?? 0 }}%
                                </td>
                                <td>
                                    {{ $partner->phone ?? '---' }}
                                </td>
                                <td>
                                    <span class="badge bg-{{ $partner->is_active ? 'success' : 'danger' }}">
                                        {{ $partner->is_active
                                            ? __('common.active')
                                            : __('common.inactive')
                                        }}
                                    </span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <a href="{{ route('partners.edit', $partner->id) }}"
                                            class="btn btn-sm btn-success d-inline-flex align-items-center">
                                            <i class="bi bi-pencil-square me-1"></i>
                                            <span>
                                                {{ __('common.edit') }}
                                            </span>
                                        </a>
                                        <form action="{{ route('partners.destroy', $partner->id) }}"
                                            method="POST"
                                            class="delete-form d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="btn btn-sm btn-danger d-inline-flex align-items-center">
                                                <i class="bi bi-trash me-1"></i>
                                                <span>
                                                    {{ __('common.delete') }}
                                                </span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7"
                                    class="text-center py-5 text-muted">
                                    <i class="bi bi-people-fill fs-2 d-block mb-2"></i>
                                    {{ __('partners.no_partners_found') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-3">
                    {{ $partners->links() }}
                </div>
            </div>
        </div>
    </div>
</x-main>