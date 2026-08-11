<x-main page="Collections">
    <x-slot name="header">
        <x-header />
    </x-slot>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1 page-title">{{ __('common.add_collection') }}</h4>
                </div>
                <div>
                    <a href="{{ route('collections.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left-square"></i>
                        {{ __('common.back') }}
                    </a>
                </div>
            </div>
            <hr class="thick-hr">
            <form action="{{ route('collections.store') }}" class="w-100" method="POST"
                onsubmit="disableSubmitButton(this)">
                @csrf
                <div class="row">
                    {{-- driver --}}
                    <div class="col-12 col-md-6 mt-3">
                        <div class="row">
                            <div class="col-3">
                                <label for="driver"
                                    class="add-form-label py-2 @error('driver') text-danger @enderror">
                                    {{ __('common.driver') }}</label>
                            </div>
                            <div class="col-9 @error('driver') select2-danger @enderror">
                                <select name="driver" class="form-control select2" style="width: 100%" id="driver">
                                    <option value="">{{ __('settlements.select_driver') }}</option>
                                    @foreach ($drivers as $driver)
                                        <option value="{{ $driver->id }}"
                                            {{ $driver->id == old('driver') ? 'selected' : '' }}>
                                            {{ $driver->name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger" id="main_account_error">
                                    @error('driver')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                        </div>
                    </div>
                    {{-- Payment Method --}}
                    <div class="col-12 col-md-6 mt-3">
                        <div class="row">
                            <div class="col-3">
                                <label for="payment_method"
                                    class="add-form-label py-2 @error('payment_method') text-danger @enderror">
                                    {{ __('transactions.payment_method') }}</label>
                            </div>
                            <div class="col-9">
                                <select class="form-select @error('payment_method') border-danger @enderror"
                                    id="payment_method" name="payment_method">
                                    <option value="">{{ __('transactions.select_payment_method') }}</option>
                                    @foreach ($paymentMethods as $paymentMethod)
                                        <option value="{{ $paymentMethod->id }}"
                                            {{ $paymentMethod->id == old('payment_method') ? 'selected' : '' }}>
                                            {{ $paymentMethod->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="text-danger" id="payment_method_error">
                                    @error('payment_method')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                        </div>
                    </div>
                    {{-- currency --}}
                    <div class="col-12 col-md-6 mt-3">
                        <div class="row">
                            <div class="col-3">
                                <label for="currency"
                                    class="add-form-label py-2 @error('currency') text-danger @enderror">{{ __('transactions.currency') }}</label>
                            </div>
                            <div class="col-9">
                                <select class="form-select @error('currency') border-danger @enderror" id="currency"
                                    name="currency">
                                    <option value="">{{ __('transactions.select_currency') }}</option>
                                    @foreach ($currencies as $currency)
                                        <option value="{{ $currency->id }}"
                                            {{ $currency->id == old('currency') ? 'selected' : '' }}>
                                            {{ $currency->symbol }} - {{ $currency->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="text-danger" id="currency_error">
                                    @error('currency')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                        </div>
                    </div>
                    {{-- total_amount --}}
                    <div class="col-12 col-md-6 mt-3">
                        <div class="row">
                            <div class="col-3">
                                <label
                                    for="total_amount"class="add-form-label py-2  @error('total_amount') text-danger @enderror">
                                    {{ __('common.total_amount') }}
                                </label>
                            </div>
                            <div class="col-9">
                                <input type="number" step="0.01" min="0"
                                    class="form-control  @error('total_amount') border-danger @enderror"
                                    value="{{ old('total_amount') }}" id="total_amount" name="total_amount"
                                    placeholder="{{ __('common.enter_total_amount') }}">
                                <span class="text-danger" id="total_amount_error">
                                    @error('total_amount')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                        </div>
                    </div>
                    {{-- driver_amount --}}
                    <div class="col-12 col-md-6 mt-3">
                        <div class="row">
                            <div class="col-3">
                                <label
                                    for="driver_amount"class="add-form-label py-2  @error('driver_amount') text-danger @enderror">
                                    {{ __('common.driver_amount') }}
                                </label>
                            </div>
                            <div class="col-9">
                                <input type="number" step="0.01" min="0"
                                    class="form-control  @error('driver_amount') border-danger @enderror"
                                    value="{{ old('driver_amount') }}" id="driver_amount" name="driver_amount"
                                    placeholder="{{ __('common.enter_driver_amount') }}">
                                <span class="text-danger" id="driver_amount_error">
                                    @error('driver_amount')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                        </div>
                    </div>
                    {{-- date --}}
                    <div class="col-12 col-md-6 mt-3">
                        <div class="row">
                            <div class="col-3">
                                <label for="date"class="add-form-label py-2  @error('date') text-danger @enderror">
                                    {{ __('common.date') }}
                                </label>
                            </div>
                            <div class="col-9">
                                <input type="date" class="form-control  @error('date') border-danger @enderror"
                                    value="{{ old('date') }}" id="date" name="date"
                                    placeholder="Enter driver amount">
                                <span class="text-danger" id="date_error">
                                    @error('date')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                        </div>
                    </div>
                    <hr class="mt-3">
                    {{-- notes --}}
                    <div class="col-12 mt-3">
                        <div class="row mt-1">
                            <div class="col-md-1 col-3">
                                <label for="notes"
                                    class="add-form-label py-2 @error('notes') text-danger @enderror">
                                    {{ __('common.note') }}
                                </label>
                            </div>
                            <div class="col-md-11 col-9 ps-lg-5 d-flex">
                                <textarea name="notes" class="ms-lg-2 ms-md-4 form-control me-1 @error('notes') border-danger @enderror"
                                    id="notes">{{ old('notes') }}</textarea>
                            </div>
                            <div class="col-md-1 col-3"></div>
                            <div class="col-md-11 col-9 ps-lg-5 d-flex">
                                <span class="text-danger ms-lg-2 ms-md-4 " id="notes_error">
                                    @error('notes')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                        </div>
                    </div>
                    <hr class="mt-4">
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-main shadow">
                            <span class="btn-text">
                                <i class="bi bi-floppy me-2"></i>
                                {{ __('common.save') }}
                            </span>
                            <span class="btn-loading d-none">
                                {{ __('common.save') }}...
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-main>
