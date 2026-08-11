<x-main page="Settlements">
    <x-slot name="header">
        <x-header />
    </x-slot>
    <input type="hidden" id="currency_rounding" value="{{ $defaultCurrency->rounding_unit }}">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1 page-title">{{ __('settlements.add_settlement') }}</h4>
                </div>
                <div>
                    <a href="{{ route('settlements.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left-square"></i>
                        {{ __('settlements.back') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h6 class="mb-1 page-title">{{ __('settlements.information') }}</h6>
                </div>
            </div>
            <hr class="thick-hr">
            <div class="row">
                <div class="col-md-3">
                    <label>{{ __('settlements.driver') }}</label>
                    <select class="form-control" id="driver_id">
                        <option value="">
                            {{ __('settlements.select_driver') }}
                        </option>
                        @foreach ($drivers as $driver)
                            <option value="{{ $driver->id }}" data-type="{{ $driver->driver_type }}"
                                data-percentage="{{ $driver->driver_percentage }}">
                                {{ $driver->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>{{ __('settlements.type') }}</label>
                    <input type="text" class="form-control" id="type" readonly>
                </div>
                <div class="col-md-3">
                    <label>{{ __('settlements.percentage') }}</label>
                    <input type="number" class="form-control" id="driver_percentage" readonly>
                </div>
                <div class="col-md-3">
                    <label>{{ __('settlements.date') }}</label>
                    <input type="date" id="settlement_date" class="form-control">
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-12">
                    <label>{{ __('settlements.notes') }}</label>
                    <textarea class="form-control" rows="2" id="notes"></textarea>
                </div>
            </div>
            <div class="col-md-12 mt-3 text-end">
                <button type="button" id="addOrders" class="btn btn-main">
                    {{ __('settlements.add_orders') }}
                </button>
            </div>
        </div>
    </div>
    <div class="card shadow-sm mb-4 d-none" id="orders_section">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1 page-title">{{ __('settlements.orders') }}</h4>
                </div>
            </div>
            <hr class="thick-hr">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th width="5%">
                                #
                            </th>
                            <th width="20%">
                                {{ __('settlements.currency') }}
                            </th>
                            <th width="20%">
                                {{ __('settlements.contract_company') }}
                            </th>
                            <th width="15%">
                                {{ __('settlements.delivery_fee') }}
                            </th>
                            <th width="15%">
                                {{ __('settlements.driver_amount') }}
                            </th>
                            <th width="15%">
                                {{ __('settlements.company') }}
                            </th>
                            <th width="10%">
                                {{ __('settlements.action') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody id="orders_body">
                        <tr class="order-row editable">
                            <td>
                                1
                            </td>
                            <td>
                                <select class="form-control currency">
                                    @foreach ($currencies as $currency)
                                        <option value="{{ $currency->id }}" data-rate="{{ $currency->rate }}"
                                            data-input="{{ $currency->input_mode }}"
                                            @if ($currency->is_default) selected @endif>
                                            {{ $currency->symbol }} - {{ $currency->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select class="form-control partner">
                                    <option value="">
                                        {{ __('settlements.select_partner') }}
                                    </option>
                                    @foreach ($companies as $company)
                                        <option value="{{ $company->id }}" data-type="{{ $company->fee_type }}"
                                            data-percentage="{{ $company->percentage }}"
                                            data-fixed="{{ $company->fixed_fee }}">
                                            {{ $company->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="number" class="form-control delivery_fee">
                            </td>
                            <td>
                                <input type="number" class="form-control driver_amount" readonly>
                            </td>
                            <td>
                                <input type="number" class="form-control company_amount" readonly>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm remove-row">
                                    X
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <hr>
            <div class="row mt-4">
                <div class="col-md-2">
                    <strong>
                        {{ __('settlements.orders') }}:
                    </strong>
                    <span id="orders_count">
                        0
                    </span>
                </div>
                <div class="col-md-3">
                    <strong>
                        {{ __('settlements.total') }} ({{ $defaultCurrency->symbol }}):
                    </strong>
                    <span id="total">
                        0
                    </span>
                </div>
                <div class="col-md-3">
                    <strong>
                        {{ __('settlements.subtotal') }} ({{ $defaultCurrency->symbol }}):
                    </strong>
                    <span id="subtotal">
                        0
                    </span>
                </div>
                <div class="col-md-2">
                    <strong>
                        {{ __('settlements.driver') }} ({{ $defaultCurrency->symbol }}):
                    </strong>
                    <span id="driver_total">
                        0
                    </span>
                </div>
                <div class="col-md-2">
                    <strong>
                        {{ __('settlements.company') }} ({{ $defaultCurrency->symbol }}):
                    </strong>
                    <span id="company_total">
                        0
                    </span>
                </div>
            </div>
            <hr>
            <div class="col-md-12 mt-3 text-end">
                <button class="btn btn-main btn-block mt-4" id="save_settlement">
                    <i class="bi bi-floppy me-2"> </i>
                    {{ __('settlements.save') }}
                </button>
            </div>
        </div>
    </div>
    <script>
        let currencies = @json($currencies);
        let companies = @json($companies);
        window.routes = {
            settlementStore: "{{ route('settlements.store') }}",
            settlementIndex: "{{ route('settlements.index') }}"
        };
    </script>
    <script>
        window.lang = {
            selectPartner: @json(__('settlements.select_partner')),
        };
    </script>
    @vite(['resources/js/scripts.js'])
</x-main>
