<x-main page="Settlements">
    <x-slot name="header">
        <x-header />
    </x-slot>
    <div class="container-fluid" id="print-area">
        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4 print-header">
            <div>
                <h3 class="mb-1">
                    {{ __('common.settlement') }} #{{ $settlement->settlement_num }} <span
                        class="badge bg-{{ $settlement->status == 'pending' ? 'secondary' : 'success' }} fs-6">{{ $settlement->status }}</span>
                </h3>
                <small class="text-muted">
                    {{ \Carbon\Carbon::parse($settlement->date)->format('d-m-Y') }}
                </small>
            </div>
            <div>
                <a href="{{ route('settlements.print', $settlement) }}" target="_blank"
                    class="btn btn-main">
                    <i class="fas fa-print"></i> {{ __('common.print') }}
                </a>
                <a href="{{ route('settlements.edit', $settlement) }}" class="btn btn-success no-print">
                    <i class="fas fa-edit"></i> {{ __('common.edit') }}
                </a>
            </div>
        </div>
        {{-- Driver Information --}}
        <div class="card shadow-sm mb-4 print-header">
            <div class="card-header">
                <strong>{{ __('common.driver_information') }}</strong>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <strong>{{ __('common.driver') }}</strong>
                        <br>
                        {{ $settlement->driver->name }}
                    </div>
                    <div class="col-md-3">
                        <strong>{{ __('common.date') }}</strong>
                        <br>
                        {{ $settlement->date }}
                    </div>
                    <div class="col-md-3">
                        <strong>{{ __('common.salary') }}</strong>
                        <br>
                        {{ $settlement->driver?->salary ?? '0.00' }} {{ $defaultCurrency->symbol }}
                    </div>
                    <div class="col-md-3">
                        <strong>{{ __('common.driver_percentage') }}</strong>
                        <br>
                        {{ $settlement->driver_percentage ?? '0' }}%
                    </div>
                </div>
            </div>
        </div>
        @if ($settlement->notes)
            {{-- Note --}}
            <div class="card shadow-sm mb-4 print-header">
                <div class="card-header">
                    <strong>{{ __('common.note') }}</strong>
                </div>
                <div class="card-body">
                    <p>{!! $settlement->notes !!}</p>
                </div>
            </div>
        @endif
        {{-- Details --}}
        {{-- @foreach ($settlements as $settlement) --}}
        <div class="card shadow-sm mb-4 print-header">
            <div class="card-header d-flex justify-content-between">
                <div>
                    <strong>{{ __('common.orders') }}</strong>
                </div>
            </div>
            <div class="card-body">
                {{-- Orders --}}
                <div class="table-responsive shadow-sm">
                    <table class="table table-bordered table-hover text-nowrap">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>{{ __('common.order_number') }}</th>
                                <th>{{ __('common.currency') }} ({{ __('common.rate') }})</th>
                                <th>{{ __('common.partner') }}</th>
                                <th>{{ __('common.delivery_fee') }}</th>
                                <th>{{ __('common.partner_share') }}</th>
                                <th>{{ __('common.driver_share') }}</th>
                                <th>{{ __('common.company_share') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($settlement->orders as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->order_num }}</td>
                                    <td>{{ $item->currency->name }} - {{ $item->currency->symbol }}
                                        ({{ rtrim(rtrim(number_format($item->exchange_rate, 6), '0'), '.') }})
                                    </td>
                                    <td> {{ $item->contractCompany?->name }}
                                        @if ($item->contractCompany?->fee_type == 'fixed')
                                            ({{ $item->contractCompany?->fixed_fee }} {{ $defaultCurrency->symbol }})
                                        @elseif($item->contractCompany?->fee_type == 'percentage')
                                            ( {{ $item->contractCompany?->percentage }} %)
                                        @else
                                            ---
                                        @endif
                                    </td>
                                    <td>
                                        {{ number_format($item->delivery_fee ?? 0, 2) }}
                                    </td>
                                    <td>
                                        {{ number_format($item->contract_company_amount ?? 0, 2) }}
                                    </td>
                                    <td>
                                        {{ number_format($item->driver_amount ?? 0, 2) }}
                                    </td>
                                    <td>
                                        {{ number_format($item->company_amount ?? 0, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        {{-- @endforeach --}}
        {{-- Settlement Totals --}}
        <div class="card shadow-sm print-header">
            <div class="card-header d-flex justify-content-between">
                <div>
                    <strong>{{ __('common.settlement_totals') }}</strong>
                </div>
                <div>
                    <span class="badge bg-main">
                        {{ $defaultCurrency->symbol }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-2">
                        <h6>{{ __('common.total_orders') }}</h6>
                        <h4>
                            {{ $settlement->total_orders }}
                        </h4>
                    </div>
                    <div class="col-md-2">
                        <h6>{{ __('common.driver_earnings') }}</h6>
                        <h4 class="text-success">
                            {{ number_format($settlement->driver_total ?? 0, 2) }}
                        </h4>
                    </div>
                    <div class="col-md-2">
                        <h6>{{ __('common.company_earnings') }}</h6>
                        <h4 class="text-success">
                            {{ number_format($settlement->company_total ?? 0, 2) }}
                        </h4>
                    </div>
                    <div class="col-md-2">
                        <h6>{{ __('common.subtotal') }}</h6>
                        <h4>
                            {{ number_format($settlement->subtotal, 2) }}
                        </h4>
                    </div>
                    <div class="col-md-2">
                        <h6>{{ __('common.partners_earnings') }}</h6>
                        <h4 class="text-success">
                            {{ number_format($settlement->contract_company_total, 2) }}
                        </h4>
                    </div>
                    <div class="col-md-2">
                        <h6>{{ __('common.total') }}</h6>
                        <h4>
                            {{ number_format($settlement->delivery_total, 2) }}
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-main>
