<x-main page="Transactions">
    <x-slot name="header">
        <x-header />
    </x-slot>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1 page-title">
                        {{ __('transactions.add_transaction') }}
                    </h4>
                </div>
                <div>
                    <a href="{{ route('transactions.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left-square"></i>
                        {{ __('common.back') }}
                    </a>
                </div>
            </div>
            <hr class="thick-hr">
            <form action="{{ route('transactions.store') }}" class="w-100" method="POST"
                onsubmit="disableSubmitButton(this)">
                @csrf
                <div class="row">
                    {{-- type --}}
                    <div class="col-12 col-md-6 mt-3">
                        <div class="row">
                            <div class="col-3">
                                <label for="type" class="add-form-label py-2 @error('type') text-danger @enderror">
                                    {{ __('transactions.type') }}
                                </label>
                            </div>
                            <div class="col-9 @error('type') select2-danger @enderror">
                                <select name="type" class="form-control select2"
                                    style="width:100%" id="type">
                                    <option value="">
                                        {{ __('transactions.select_type') }}
                                    </option>
                                    <option value="payment" {{ old('type') == 'payment' ? 'selected' : '' }}>
                                        {{ __('transactions.payment') }} ({{ __('common.out') }})
                                    </option>
                                    <option value="receipt" {{ old('type') == 'receipt' ? 'selected' : '' }}>
                                        {{ __('transactions.receipt') }} ({{ __('common.in') }})
                                    </option>
                                    <option value="expenses" {{ old('type') == 'expenses' ? 'selected' : '' }}>
                                        {{ __('transactions.expenses') }} ({{ __('common.out') }})
                                    </option>
                                    <option value="adjustment" {{ old('type') == 'adjustment' ? 'selected' : '' }}>
                                        {{ __('transactions.adjustment') }}
                                    </option>
                                    <option value="refund" {{ old('type') == 'refund' ? 'selected' : '' }}>
                                        {{ __('transactions.refund') }} ({{ __('common.out') }})
                                    </option>
                                    <option value="others" {{ old('type') == 'others' ? 'selected' : '' }}>
                                        {{ __('transactions.others') }}
                                    </option>
                                </select>
                                <span class="text-danger">
                                    @error('type')
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
                                <label for="date" class="add-form-label py-2 @error('date') text-danger @enderror">
                                    {{ __('common.date') }}
                                </label>
                            </div>
                            <div class="col-9">
                                <input type="date" class="form-control @error('date') border-danger @enderror"
                                    value="{{ old('date') }}" id="date" name="date">
                                <span class="text-danger">
                                    @error('date')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                        </div>
                    </div>
                    {{-- direction --}}
                    <div class="col-12 col-md-6 mt-3">
                        <div class="row">
                            <div class="col-3">
                                <label for="direction"
                                    class="add-form-label py-2 @error('direction') text-danger @enderror">
                                    {{ __('transactions.direction') }}
                                </label>
                            </div>
                            <div class="col-9">
                                <select name="direction" class="form-control @error('direction') border-danger @enderror" id="direction">
                                    <option value="">
                                        {{ __('transactions.select_direction') }}
                                    </option>
                                    <option value="in" {{ old('direction') == 'in' ? 'selected' : '' }}>
                                        {{ __('common.in') }}
                                    </option>
                                    <option value="out" {{ old('direction') == 'out' ? 'selected' : '' }}>
                                        {{ __('common.out') }}
                                    </option>
                                </select>
                                <span class="text-danger">
                                    @error('direction')
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
                                    class="add-form-label py-2 @error('currency') text-danger @enderror">
                                    {{ __('common.currency') }}
                                </label>
                            </div>
                            <div class="col-9">
                                <select name="currency" class="form-control @error('currency') border-danger @enderror" id="currency">
                                    <option value="">
                                        {{ __('transactions.select_currency') }}
                                    </option>
                                    @foreach ($currencies as $currency)
                                        <option value="{{ $currency->id }}"
                                            {{ $currency->id == old('currency') ? 'selected' : '' }}>
                                            {{ $currency->name }} - {{ $currency->symbol }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="text-danger">
                                    @error('currency')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                        </div>
                    </div>
                    {{-- amount --}}
                    <div class="col-12 col-md-6 mt-3">
                        <div class="row">
                            <div class="col-3">
                                <label for="amount"
                                    class="add-form-label py-2 @error('amount') text-danger @enderror">
                                    {{ __('common.amount') }}
                                </label>
                            </div>
                            <div class="col-9">
                                <input type="number" step="1" min="0"
                                    class="form-control @error('amount') border-danger @enderror"
                                    value="{{ old('amount') }}" id="amount" name="amount"
                                    placeholder="{{ __('transactions.enter_amount') }}">
                                <span class="text-danger">
                                    @error('amount')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                        </div>
                    </div>
                    {{-- partner --}}
                    <div class="col-12 col-md-6 mt-3">
                        <div class="row">
                            <div class="col-3">
                                <label for="partner"
                                    class="add-form-label py-2 @error('partner') text-danger @enderror">
                                    {{ __('transactions.partner') }}
                                </label>
                            </div>
                            <div class="col-9 @error('partner') select2-danger @enderror">
                                <select name="partner" class="form-control select2" style="width:100%" id="partner">
                                    <option value="">
                                        {{ __('transactions.select_partner') }}
                                    </option>
                                    @foreach ($partners as $partner)
                                        <option value="{{ $partner->id }}"
                                            {{ $partner->id == old('partner') ? 'selected' : '' }}>
                                            {{ $partner->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="text-danger">
                                    @error('partner')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                        </div>
                    </div>
                    {{-- driver --}}
                    <div class="col-12 col-md-6 mt-3">
                        <div class="row">
                            <div class="col-3">
                                <label for="driver"
                                    class="add-form-label py-2 @error('driver') text-danger @enderror">
                                    {{ __('transactions.driver') }}
                                </label>
                            </div>
                            <div class="col-9 @error('driver') select2-danger @enderror">
                                <select name="driver" class="form-control select2" style="width:100%"
                                    id="driver">
                                    <option value="">
                                        {{ __('transactions.select_driver') }}
                                    </option>
                                    @foreach ($drivers as $driver)
                                        <option value="{{ $driver->id }}"
                                            {{ $driver->id == old('driver') ? 'selected' : '' }}>
                                            {{ $driver->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="text-danger">
                                    @error('driver')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                        </div>
                    </div>
                    {{-- user --}}
                    <div class="col-12 col-md-6 mt-3">
                        <div class="row">
                            <div class="col-3">
                                <label for="user"
                                    class="add-form-label py-2 @error('user') text-danger @enderror">
                                    {{ __('transactions.user') }}
                                </label>
                            </div>
                            <div class="col-9 @error('driver') select2-danger @enderror">
                                <select name="user" class="form-control select2" style="width:100%"
                                    id="user">
                                    <option value="">
                                        {{ __('transactions.select_user') }}
                                    </option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}"
                                            {{ $user->id == old('user') ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="text-danger">
                                    @error('user')
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
                                    {{ __('transactions.payment_method') }}
                                </label>
                            </div>
                            <div class="col-9">
                                <select class="form-select @error('payment_method') border-danger @enderror"
                                    id="payment_method" name="payment_method">
                                    <option value="">
                                        {{ __('transactions.select_payment_method') }}
                                    </option>
                                    @foreach ($paymentMethods as $paymentMethod)
                                        <option value="{{ $paymentMethod->id }}"
                                            {{ $paymentMethod->id == old('payment_method') ? 'selected' : '' }}>
                                            {{ $paymentMethod->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="text-danger">
                                    @error('payment_method')
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
                            <div class="col-md-11 col-9 ps-lg-5">
                                <textarea name="notes" class="form-control @error('notes') border-danger @enderror" id="notes">{{ old('notes') }}</textarea>
                            </div>
                            <div class="col-md-1 col-3"></div>
                            <div class="col-md-11 col-9 ps-lg-5">
                                <span class="text-danger">
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
                                {{ __('common.saving') }}...
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-main>