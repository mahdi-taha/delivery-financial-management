<x-main page="Drivers">
    <x-slot name="header">
        <x-header />
    </x-slot>
    <div class="container-fluid">
        <div class="driver-header mb-4 d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <div class="driver-avatar me-3">
                    {{ strtoupper(substr($driver->name, 0, 1)) }}
                </div>
                <div>
                    <h3 class="mb-1">
                        {{ $driver->name }}
                        <span class="badge bg-{{ $driver->is_active ? 'success' : 'secondary' }}">
                            {{ $driver->is_active ? __('common.active') : __('common.inactive') }}
                        </span>
                    </h3>
                    <div class="text-muted">
                        @if ($driver->phone)
                            <i class="bi bi-telephone"></i>
                            {{ $driver->phone }}
                        @endif
                    </div>
                </div>
            </div>
            <div>
                <a href="{{ route('drivers.edit', $driver->id) }}" class="btn btn-secondary">
                    <i class="bi bi-pencil-square"></i>
                    {{ __('common.edit') }}
                </a>
            </div>
        </div>
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card info-card shadow-sm p-3">
                    <div class="info-label">
                        {{ __('drivers.driver_type') }}
                    </div>
                    <div class="info-value">
                        @if ($driver?->driver_type === 'custom')
                            {{ __('drivers.' . $driver->driver_type) }}
                        @else
                            {{ __('common.' . $driver->driver_type) ?? '---' }}
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card info-card shadow-sm p-3">
                    <div class="info-label">
                        {{ __('common.percentage') }}
                    </div>
                    <div class="info-value">
                        {{ $driver->driver_percentage ?? 0 }}%
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card info-card shadow-sm p-3">
                    <div class="info-label">
                        {{ __('common.salary') }}
                    </div>
                    <div class="info-value">
                        {{ number_format($driver->salary ?? 0, 2) }} {{ $defaultCurrency->symbol }}
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card info-card shadow-sm p-3">
                    <div class="info-label">
                        {{ __('common.note') }}
                    </div>
                    <div class="info-value">
                        {{ $driver->notes ?? '-' }}
                    </div>
                </div>
            </div>
        </div>
        <div class="card shadow-sm pending-card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div class="section-title">
                    <i class="bi bi-hourglass-split me-2"></i>
                    {{ __('common.pending_settlements') }} ({{ $defaultCurrency->symbol }})
                    <span class="badge bg-warning text-dark ms-2">
                        {{ $pendingSettlements->count() }}
                    </span>
                </div>
                <button id="payBtn" class="btn btn-success btn-sm pay-btn cursor-pointer" disabled>
                    <i class="bi bi-cash-stack me-1"></i>
                    {{ __('drivers.receive_selected') }}
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 settlement-table text-nowrap">
                        <thead>
                            <tr>
                                <th class="text-center">
                                    <input type="checkbox" id="checkAll" class="cursor-pointer">
                                </th>
                                <th>#</th>
                                <th>{{ __('common.settlement') }}</th>
                                <th>{{ __('common.date') }}</th>
                                <th class="text-center">
                                    {{ __('common.orders') }}
                                </th>
                                <th>
                                    {{ __('common.driver_amount') }}
                                </th>
                                <th>
                                    {{ __('common.company_gain') }}
                                </th>
                                <th>
                                    {{ __('common.actions') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pendingSettlements as $settlement)
                                <tr>
                                    <td class="text-center">
                                        <input type="checkbox" class="settlement-check cursor-pointer"
                                            value="{{ $settlement->id }}"
                                            data-amount="{{ $settlement->company_total }}">
                                    </td>
                                    <td>
                                        {{ $loop->iteration }}
                                    </td>
                                    <td class="settlement-num">
                                        <i class="bi bi-receipt me-1 text-muted"></i>
                                        {{ $settlement->settlement_num }}
                                    </td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($settlement->date)->format('d-m-Y') }}
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-light text-dark">
                                            {{ $settlement->total_orders }}
                                        </span>
                                    </td>
                                    <td class="amount-driver">
                                        {{ number_format($settlement->driver_total, 2) }}
                                    </td>
                                    <td class="amount-company">
                                        {{ number_format($settlement->company_total, 2) }}
                                    </td>
                                    <td>
                                        <a href="{{ route('settlements.show', $settlement->id) }}"
                                            class="btn btn-main btn-sm">
                                            <i class="bi bi-eye"></i>
                                            {{ __('common.view') }}
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-muted py-4">
                                        <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                        {{ __('drivers.no_pending_settlements') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        let payBtn = document.getElementById('payBtn');
        let checkAll = document.getElementById('checkAll');
        function updateTotal() {
            let total = 0;
            let checked = document.querySelectorAll('.settlement-check:checked');
            checked.forEach(el => {
                total += parseFloat(el.dataset.amount);
            });
            payBtn.disabled = checked.length === 0;
            const receiveText = "{{ __('drivers.receive_selected') }}";
            payBtn.innerHTML = checked.length > 0 ?
                `${receiveText} (${total.toFixed(2)})` :
                receiveText;
        }
        document.querySelectorAll('.settlement-check').forEach(cb => {
            cb.addEventListener('change', updateTotal);
        });
        checkAll.addEventListener('change', function() {
            document.querySelectorAll('.settlement-check').forEach(cb => {
                cb.checked = this.checked;
            });
            updateTotal();
        });
        payBtn.addEventListener('click', function() {
            let selected = Array.from(document.querySelectorAll('.settlement-check:checked'))
                .map(el => el.value);
            let total = Array.from(document.querySelectorAll('.settlement-check:checked'))
                .reduce((sum, el) => sum + parseFloat(el.dataset.amount), 0);
            const amount = total.toFixed(2) + ' {{ $defaultCurrency->symbol }}';
            const text = "{{ __('common.receive_confirmation_text', ['amount' => ':amount']) }}"
                .replace(':amount', amount);
            Swal.fire({
                icon: 'warning',
                title: "{{ __('common.confirm_receive') }}",
                text: text,
                showCancelButton: true,
                confirmButtonText: "{{ __('common.yes_receive') }}",
                cancelButtonText: "{{ __('common.cancel') }}",
                confirmButtonColor: '#36454f',
                cancelButtonColor: '#6c757d',
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch("{{ route('settlements.pay') }}", {
                            method: "POST",
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                settlements: selected
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            Swal.fire({
                                icon: 'success',
                                title: data.message,
                                showConfirmButton: true,
                                confirmButtonColor: '#36454f',
                            }).then(() => location.reload());
                        });
                }
            });
        });
    </script>
</x-main>
