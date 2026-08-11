<x-main page="Collections">
    <x-slot name="header">
        <x-header />
    </x-slot>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1">{{ __('common.collections') }}</h4>
                    <p class="text-muted mb-0">
                        {{ __('collections.manage_your_collections') }}
                    </p>
                </div>
                <a href="{{ route('collections.create') }}" class="btn btn-main">
                    <i class="bi bi-plus-square"></i>
                    {{ __('collections.add_collection') }}
                </a>
            </div>
            <hr class="thick-hr">
            <form method="GET" action="{{ route('collections.index') }}" class="row g-3">
                <div class="col-md-6 col-lg-4">
                    <input type="text" name="search" class="form-control" placeholder="{{ __('common.search') }}..."
                        value="{{ request('search') }}">
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <select name="payment_method_id" id="paymentMethod" class="form-select">
                        <option value="">
                            {{ __('collections.all_payment_methods') }}
                        </option>
                        @foreach ($paymentMethods as $paymentMethod)
                            <option value="{{ $paymentMethod->id }}"
                                {{ request('payment_method_id') == $paymentMethod->id ? 'selected' : '' }}>
                                {{ $paymentMethod->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 col-lg-3">
                    <select name="driver_id" id="driver" class="form-select select2">
                        <option value="">
                            {{ __('common.all_drivers') }}
                        </option>
                        @foreach ($drivers as $driver)
                            <option value="{{ $driver->id }}"
                                {{ request('driver_id') == $driver->id ? 'selected' : '' }}>
                                {{ $driver->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 col-lg-2">
                    <select name="currency_id" id="currency" class="form-select">
                        <option value="">
                            {{ __('common.all_currencies') }}
                        </option>
                        @foreach ($currencies as $currency)
                            <option value="{{ $currency->id }}"
                                {{ request('currency_id') == $currency->id ? 'selected' : '' }}>
                                {{ $currency->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 col-lg-2">
                    <select name="status" class="form-select">
                        <option value="">
                            {{ __('common.all_status') }}
                        </option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                            {{ __('common.pending') }}
                        </option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>
                            {{ __('common.paid') }}
                        </option>
                    </select>
                </div>
                <div class="col-md-3 col-lg-3">
                    <input type="date" name="from" class="form-control" value="{{ request('from') }}">
                </div>
                <div class="col-md-3 col-lg-3">
                    <input type="date" name="to" class="form-control" value="{{ request('to') }}">
                </div>
                <div class="col-md-3 col-lg-4 d-flex gap-2">
                    <button class="btn btn-main w-100">
                        <i class="bi bi-funnel me-1"></i>
                        {{ __('common.filter') }}
                    </button>
                    <a href="{{ route('collections.index') }}" class="btn border btn-light w-100">
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
                <table class="table table-hover align-middle  text-nowrap">
                    <thead class="table-head">
                        <tr>
                            <th>#</th>
                            <th>{{ __('collections.payment_method') }}</th>
                            <th>{{ __('common.driver') }}</th>
                            <th>{{ __('collections.total_amount') }}</th>
                            <th>{{ __('collections.driver_amount') }}</th>
                            <th>{{ __('collections.company_amount') }}</th>
                            <th>{{ __('collections.rate') }}</th>
                            <th>{{ __('common.date') }}</th>
                            <th>{{ __('common.status') }}</th>
                            <th width="80">{{ __('common.actions') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($collections as $collection)
                            <tr class="table-row">
                                <td>
                                    <div class="fw-semibold">
                                        {{ $collection->collection_num ?? '---' }}
                                    </div>
                                    @if ($collection->notes)
                                        <small class="text-muted">
                                            {{ $collection->notes }}
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    {{ $collection->paymentMethod?->name ?? '---' }}
                                </td>
                                <td>
                                    {{ $collection->driver?->name ?? '---' }}
                                </td>
                                <td>
                                    {{ number_format($collection->received_amount ?? 0, 2) }}
                                    {{ $collection->currency?->symbol }}
                                </td>
                                <td>
                                    {{ number_format($collection->driver_amount ?? 0, 2) }}
                                    {{ $collection->currency?->symbol }}
                                </td>
                                <td>
                                    {{ number_format($collection->company_amount ?? 0, 2) }}
                                    {{ $collection->currency?->symbol }}
                                </td>
                                <td>
                                    {{ rtrim(rtrim(number_format($collection->currency->rate, 6), '0'), '.') }}
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($collection->date)->format('d-m-Y') }}
                                </td>
                                <td>
                                    @if ($collection->status === 'pending')
                                        <span class="badge bg-secondary">
                                            {{ __('common.pending') }}
                                        </span>
                                    @else
                                        <span class="badge bg-success">
                                            {{ __('common.paid') }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if ($collection->status != 'paid')
                                        <form action="{{ route('collections.update', $collection->id) }}"
                                            method="POST" class="pay-form d-inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="collection" value="{{ $collection->id }}">
                                            <button type="submit" data-amount="{{ $collection->received_amount }}"
                                                data-currency="{{ $collection->currency->symbol }}"
                                                class="btn btn-sm btn-success d-inline-flex align-items-center">
                                                <i class="bi bi-credit-card me-1"></i>
                                                <span>
                                                    {{ __('common.pay') }}
                                                </span>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('collections.destroy', $collection->id) }}" method="POST"
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
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center py-5 text-muted">
                                    <i class="bi bi-cash-stack fs-2 d-block mb-2"></i>
                                    {{ __('collections.no_collections_found') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-3">
                    {{ $collections->links() }}
                </div>
            </div>
        </div>
    </div>
    <script>
        document.querySelectorAll('.pay-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const button = form.querySelector('button[type="submit"]');
                const amount = button.dataset.amount;
                const currency = button.dataset.currency;
                Swal.fire({
                    title: "{{ __('collections.confirm_payment') }}",
                    html: `<strong>{{ __('common.amount') }}:</strong> ${amount} ${currency}<br><br>{{ __('collections.payment_warning') }}`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: "{{ __('collections.yes_pay') }}",
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
