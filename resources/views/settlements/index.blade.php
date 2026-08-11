<x-main page="{{ __('common.settlements') }}">
    <x-slot name="header">
        <x-header />
    </x-slot>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1">{{ __('common.settlements') }}</h4>
                    <p class="text-muted mb-0">{{ __('common.manage_your_settlements') }}</p>
                </div>
                <a href="{{ route('settlements.create') }}" class="btn btn-main">
                    <i class="bi bi-plus-square"></i>
                    {{ __('common.add_settlement') }}
                </a>
            </div>
            <hr class="thick-hr">
            <form method="GET" action="{{ route('settlements.index') }}" class="row g-3">
                <div class="col-md-6 col-lg-3">
                    <input type="text" name="search" class="form-control"
                        placeholder="{{ __('common.search') }}"
                        value="{{ request('search') }}">
                </div>
                <div class="col-md-6 col-lg-3">
                    <select name="driver_id" class="form-select select2">
                        <option value="">{{ __('common.all_drivers') }}</option>
                        @foreach ($drivers as $driver)
                            <option value="{{ $driver->id }}"
                                {{ request('driver_id') == $driver->id ? 'selected' : '' }}>
                                {{ $driver->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 col-lg-2">
                    <select name="status" class="form-select">
                        <option value="">{{ __('common.all_status') }}</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                            {{ __('common.pending') }}
                        </option>
                        <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>
                            {{ __('common.closed') }}
                        </option>
                    </select>
                </div>
                <div class="col-md-4 col-lg-2">
                    <input type="date" name="date_from" class="form-control"
                        value="{{ request('date_from') }}">
                </div>
                <div class="col-md-4 col-lg-2">
                    <input type="date" name="date_to" class="form-control"
                        value="{{ request('date_to') }}">
                </div>
                <div class="col-12 col-lg-3 d-flex gap-2">
                    <button class="btn btn-main w-100">
                        <i class="bi bi-funnel me-1"></i>
                        {{ __('common.filter') }}
                    </button>
                    <a href="{{ route('settlements.index') }}" class="btn btn-light border w-100">
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
                            <th>#</th>
                            <th>{{ __('common.driver') }}</th>
                            <th>{{ __('common.date') }}</th>
                            <th>{{ __('common.orders') }}</th>
                            <th>{{ __('common.driver_amount') }} ({{ $defaultCurrency->symbol }})</th>
                            <th>{{ __('common.company_amount') }} ({{ $defaultCurrency->symbol }})</th>
                            <th>{{ __('common.subtotal') }} ({{ $defaultCurrency->symbol }})</th>
                            <th>{{ __('common.partner_amount') }} ({{ $defaultCurrency->symbol }})</th>
                            <th>{{ __('common.total') }} ({{ $defaultCurrency->symbol }})</th>
                            <th>{{ __('common.status') }}</th>
                            <th></th>
                            <th>{{ __('common.actions') }}</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($settlements as $settlement)
                            <tr class="table-row">
                                <td>
                                    <div class="fw-semibold">
                                        {{ $settlement->settlement_num ?? '---' }}
                                    </div>
                                    @if ($settlement->notes)
                                        <small class="text-muted">
                                            {{ $settlement->notes }}
                                        </small>
                                    @endif
                                </td>
                                <td>{{ $settlement->driver->name ?? '-' }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($settlement->date)->format('d-m-Y') }}
                                </td>
                                <td>{{ $settlement->total_orders }}</td>
                                <td>{{ number_format($settlement->driver_total ?? 0, 2) }}</td>
                                <td>{{ number_format($settlement->company_total ?? 0, 2) }}</td>
                                <td>{{ number_format($settlement->subtotal ?? 0, 2) }}</td>
                                <td>{{ number_format($settlement->contract_company_total ?? 0, 2) }}</td>
                                <td>{{ number_format($settlement->delivery_total ?? 0, 2) }}</td>
                                <td>
                                    @if ($settlement->status === 'pending')
                                        <span class="badge bg-secondary">
                                            {{ __('common.pending') }}
                                        </span>
                                    @else
                                        <span class="badge bg-success">
                                            {{ __('common.closed') }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if ($settlement->status != 'closed')
                                        <form action="{{ route('settlements.pay-settlement') }}"
                                            method="POST"
                                            class="pay-form d-inline">
                                            @csrf
                                            <input type="hidden"
                                                name="settlements"
                                                value="{{ $settlement->id }}">
                                            <button type="submit"
                                                data-amount="{{ $settlement->company_total }}"
                                                data-currency="{{ $settlement->defaultCurrency?->symbol }}"
                                                class="btn btn-sm btn-success d-inline-flex align-items-center">
                                                <i class="bi bi-credit-card me-1"></i>
                                                <span>{{ __('common.pay') }}</span>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('settlements.show', $settlement->id) }}"
                                        class="btn btn-sm btn-secondary d-inline-flex align-items-center">
                                        <i class="bi bi-eye me-1"></i>
                                        <span>{{ __('common.view') }}</span>
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('settlements.edit', $settlement->id) }}?back_url={{ urlencode(url()->current()) }}"
                                        class="btn btn-sm btn-success d-inline-flex align-items-center">
                                        <i class="bi bi-pencil-square me-1"></i>
                                        <span>{{ __('common.edit') }}</span>
                                    </a>
                                </td>
                                <td>
                                    <form action="{{ route('settlements.destroy', $settlement->id) }}"
                                        method="POST"
                                        class="delete-form d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="btn btn-sm btn-danger d-inline-flex align-items-center">
                                            <i class="bi bi-trash me-1"></i>
                                            <span>{{ __('common.delete') }}</span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="14" class="text-center py-5 text-muted">
                                    <i class="bi bi-journal-text fs-2 d-block mb-2"></i>
                                    {{ __('common.no_settlements_found') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-3">
                    {{ $settlements->links() }}
                </div>
            </div>
        </div>
    </div>
    @if (request('message'))
        <script>
            window.addEventListener('load', function() {
                if (window.Swal) {
                    Swal.fire({
                        toast: true,
                        icon: 'success',
                        title: @json(request('message')),
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 5500,
                        timerProgressBar: true,
                    });
                }
            });
        </script>
    @endif
    <script>
        document.querySelectorAll('.pay-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const button = form.querySelector('button[type="submit"]');
                const amount = button.dataset.amount;
                const currency = button.dataset.currency;
                Swal.fire({
                    title: "{{ __('common.confirm_payment') }}",
                    html: `<strong>{{ __('common.amount') }}:</strong> ${amount}${currency}<br><br>{{ __('common.payment_confirmation_text') }}`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: "{{ __('common.yes_pay') }}",
                    cancelButtonText: "{{ __('common.cancel') }}",
                    confirmButtonColor: '#198754',
                    cancelButtonColor: '#6c757d',
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
</x-main>