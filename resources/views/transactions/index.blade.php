<x-main page="Transactions">
    <x-slot name="header">
        <x-header />
    </x-slot>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1">
                        {{ __('common.transactions') }}
                    </h4>
                    <p class="text-muted mb-0">
                        {{ __('transactions.manage_your_transactions') }}
                    </p>
                </div>
                <div>
                    <a href="{{ route('reports.transactions.print', request()->query()) }}" target="_blank"
                        class="btn btn-main">
                        {{ __('transactions.print') }}
                    </a>
                    <a href="{{ route('transactions.create') }}" class="btn btn-main">
                        <i class="bi bi-plus-square"></i>
                        {{ __('transactions.add_transaction') }}
                    </a>
                </div>
            </div>
            <hr class="thick-hr">
            <form method="GET" action="{{ route('transactions.index') }}" class="row g-3">
                {{-- Search --}}
                <div class="col-md-4 col-lg-3">
                    <input type="text" name="search" class="form-control" placeholder="{{ __('common.search') }}..."
                        value="{{ request('search') }}">
                </div>
                {{-- Currency --}}
                <div class="col-md-4 col-lg-3">
                    <select name="currency" class="form-select">
                        <option value="">
                            {{ __('common.all_currencies') }}
                        </option>
                        @foreach ($currencies as $currency)
                            <option value="{{ $currency->id }}"
                                {{ request('currency') == $currency->id ? 'selected' : '' }}>
                                {{ $currency->name }} - {{ $currency->symbol }}
                            </option>
                        @endforeach
                    </select>
                </div>
                {{-- Payment Method --}}
                <div class="col-md-4 col-lg-3">
                    <select name="paymentMethod" class="form-select">
                        <option value="">
                            {{ __('transactions.all_payment_methods') }}
                        </option>
                        @foreach ($paymentMethods as $paymentMethod)
                            <option value="{{ $paymentMethod->id }}"
                                {{ request('paymentMethod') == $paymentMethod->id ? 'selected' : '' }}>
                                {{ $paymentMethod->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                {{-- Direction --}}
                <div class="col-md-4 col-lg-3">
                    <select name="direction" class="form-select">
                        <option value="">
                            {{ __('transactions.all_directions') }}
                        </option>
                        <option value="in" {{ request('direction') == 'in' ? 'selected' : '' }}>
                            {{ __('common.in') }}
                        </option>
                        <option value="out" {{ request('direction') == 'out' ? 'selected' : '' }}>
                            {{ __('common.out') }}
                        </option>
                    </select>
                </div>
                {{-- Types --}}
                <div class="col-md-4 col-lg-2">
                    <select name="type" class="form-select">
                        <option value="">
                            {{ __('transactions.all_types') }}
                        </option>
                        <option value="payment">
                            {{ __('transactions.payment') }}
                        </option>
                        <option value="receipt">
                            {{ __('transactions.receipt') }}
                        </option>
                        <option value="expenses">
                            {{ __('transactions.expenses') }}
                        </option>
                        <option value="adjustment">
                            {{ __('transactions.adjustment') }}
                        </option>
                        <option value="refund">
                            {{ __('transactions.refund') }}
                        </option>
                        <option value="collection">
                            {{ __('transactions.collection') }}
                        </option>
                        <option value="settlement">
                            {{ __('transactions.settlement') }}
                        </option>
                        <option value="others">
                            {{ __('transactions.others') }}
                        </option>
                    </select>
                </div>
                {{-- Status --}}
                <div class="col-md-4 col-lg-2">
                    <select name="status" class="form-select">
                        <option value="">
                            {{ __('common.all_status') }}
                        </option>
                        <option value="canceled">
                            {{ __('common.canceled') }}
                        </option>
                        <option value="completed">
                            {{ __('common.completed') }}
                        </option>
                    </select>
                </div>
                {{-- Dates --}}
                <div class="col-md-4 col-lg-2">
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-4 col-lg-2">
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                {{-- Buttons --}}
                <div class="col-12 col-lg-4 d-flex gap-2">
                    <button class="btn btn-main w-100">
                        <i class="bi bi-funnel me-1"></i>
                        {{ __('common.filter') }}
                    </button>
                    <a href="{{ route('transactions.index') }}" class="btn btn-light border w-100">
                        <i class="bi bi-arrow-counterclockwise"></i>
                        {{ __('common.reset') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">
                            {{ __('transactions.total_in') }}
                        </p>
                        <h4 class="mb-0">
                            {{ number_format($summary->total_in ?? 0, 2) }}
                        </h4>
                    </div>
                    <div class="fs-1 text-success">
                        <i class="bi bi-arrow-down-circle-fill"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">
                            {{ __('transactions.total_out') }}
                        </p>
                        <h4 class="mb-0">
                            {{ number_format($summary->total_out ?? 0, 2) }}
                        </h4>
                    </div>
                    <div class="fs-1 text-danger">
                        <i class="bi bi-arrow-up-circle-fill"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">
                            {{ __('transactions.net_cash_flow') }}
                        </p>
                        <h4 class="mb-0">
                            {{ number_format($summary->net ?? 0, 2) }}
                        </h4>
                    </div>
                    <div class="fs-1 {{ ($summary->net ?? 0) >= 0 ? 'text-primary' : 'text-warning' }}">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">
                            {{ __('common.transactions') }}
                        </p>
                        <h4 class="mb-0">
                            {{ number_format($summary->transactions_count ?? 0) }}
                        </h4>
                    </div>
                    <div class="fs-1 text-secondary">
                        <i class="bi bi-receipt"></i>
                    </div>
                </div>
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
                            <th>{{ __('transactions.direction') }}</th>
                            <th>{{ __('common.date') }}</th>
                            <th>{{ __('common.amount') }}</th>
                            <th>{{ __('common.currency') }}</th>
                            <th>{{ __('transactions.type') }}</th>
                            <th>{{ __('common.status') }}</th>
                            <th>{{ __('transactions.driver') }}</th>
                            <th>{{ __('transactions.partner') }}</th>
                            <th>{{ __('transactions.collection') }}</th>
                            <th>{{ __('transactions.settlement') }}</th>
                            <th>{{ __('transactions.user') }}</th>
                            <th>{{ __('transactions.payment_method') }}</th>
                            <th width="80">{{ __('common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transactions as $transaction)
                            <tr class="table-row">
                                <td>
                                    <div class="fw-semibold">
                                        {{ $transaction->transaction_num ?? '---' }}
                                    </div>
                                    @if ($transaction->notes)
                                        <small class="text-muted">
                                            {{ $transaction->notes }}
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    @if ($transaction->direction === 'in')
                                        <span class="badge bg-success">
                                            {{ __('common.in') }}
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            {{ __('common.out') }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($transaction->date)->format('d-m-Y') }}
                                </td>
                                <td>
                                    {{ number_format($transaction->amount ?? 0, 2) }}
                                </td>
                                <td>
                                    {{ $transaction->currency->symbol }}
                                    ({{ rtrim(rtrim(number_format($transaction->currency->rate, 6), '0'), '.') }})
                                </td>
                                <td>
                                    {{ __('transactions.' . $transaction->type) }}
                                </td>
                                <td>
                                    @if ($transaction->status === 'completed')
                                        <span class="badge bg-success">
                                            {{ __('common.completed') }}
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            {{ __('common.canceled') }}
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    {{ $transaction->driver?->name ?? '---' }}
                                </td>
                                <td>
                                    {{ $transaction->contractCompany?->name ?? '---' }}
                                </td>
                                <td>
                                    {{ $transaction->collection?->collection_num ?? '---' }}
                                </td>
                                <td>
                                    {{ $transaction->settlement?->settlement_num ?? '---' }}
                                </td>
                                <td>
                                    {{ $transaction->user?->name ?? '---' }}
                                </td>
                                <td>
                                    {{ $transaction->paymentMethod?->name ?? '---' }}
                                </td>
                                <td>
                                    @if ($transaction->status != 'canceled')
                                        <form action="{{ route('transactions.update', $transaction->id) }}"
                                            method="POST" class="d-inline cancel-transaction-form">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="actionType" value="cancel">
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-x-circle me-1"></i>
                                                {{ __('common.cancel') }}
                                            </button>
                                        </form>
                                    @else
                                        <form action="{{ route('transactions.update', $transaction->id) }}"
                                            method="POST" class="d-inline uncancel-transaction-form">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="actionType" value="restore">
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="bi bi-arrow-counterclockwise me-1"></i>
                                                {{ __('common.restore') }}
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="14" class="text-center py-5 text-muted">
                                    <i class="bi bi-receipt-cutoff fs-2 d-block mb-2"></i>
                                    {{ __('transactions.no_transactions_found') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-3">
                    {{ $transactions->links() }}
                </div>
            </div>
        </div>
    </div>
    <script>
        window.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.cancel-transaction-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        text: "{{ __('transactions.cancel_warning') }}",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: "{{ __('transactions.yes_cancel') }}",
                        cancelButtonText: "{{ __('transactions.keep') }}",
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                    }).then(result => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
            document.querySelectorAll('.uncancel-transaction-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: "{{ __('transactions.confirm_restore') }}",
                        text: "{{ __('transactions.restore_warning') }}",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: "{{ __('transactions.yes_restore') }}",
                        cancelButtonText: "{{ __('common.cancel') }}",
                        confirmButtonColor: '#198754',
                        cancelButtonColor: '#6c757d',
                    }).then(result => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
</x-main>
