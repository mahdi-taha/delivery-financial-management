<x-main page="Settings: Currencies">
    <x-slot name="header">
        <x-header />
    </x-slot>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1 page-title">{{ __('common.edit_currency') }}</h4>
                </div>
                <div>
                    <a href="{{ route('currencies.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left-square"></i>
                        {{ __('common.back') }}
                    </a>
                </div>
            </div>
            <hr class="thick-hr">
            <form action="{{ route('currencies.update', $currency->id) }}" class="w-100" method="POST"
                onsubmit="disableSubmitButton(this)">
                @csrf
                @method('PUT')
                <div class="row">
                    {{-- name --}}
                    <div class="col-12 col-md-6 mt-3">
                        <div class="row">
                            <div class="col-3">
                                <label for="name"
                                    class="add-form-label py-2  @error('name') text-danger @enderror">{{ __('common.name') }}</label>
                            </div>
                            <div class="col-9">
                                <input type="text" class="form-control  @error('name') border-danger @enderror"
                                    value="{{ old('name', $currency->name) }}" id="name" name="name"
                                    placeholder="Enter currency name">
                                <span class="text-danger" id="name_error">
                                    @error('name')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                        </div>
                    </div>
                    {{-- symbol --}}
                    <div class="col-12 col-md-6 mt-3">
                        <div class="row">
                            <div class="col-3">
                                <label for="symbol"
                                    class="add-form-label py-2  @error('symbol') text-danger @enderror">{{ __('common.symbol') }}</label>
                            </div>
                            <div class="col-9">
                                <input type="text" class="form-control  @error('symbol') border-danger @enderror"
                                    value="{{ old('symbol', $currency->symbol) }}" id="symbol" name="symbol"
                                    placeholder="Enter currency symbol">
                                <span class="text-danger" id="symbol_error">
                                    @error('symbol')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                        </div>
                    </div>
                    {{-- input_mode --}}
                    <div class="col-12 col-md-6 mt-3">
                        <div class="row">
                            <div class="col-3">
                                <label for="input_mode"
                                    class="add-form-label py-2 @error('input_mode') text-danger @enderror">
                                    {{ __('common.input_mode') }}</label>
                            </div>
                            <div class="col-9">
                                <select class="form-select @error('input_mode') border-danger @enderror" id="input_mode"
                                    name="input_mode">
                                    <option value="normal" {{ 'normal' == old('input_mode', $currency->input_mode) ? 'selected' : '' }}>Select
                                        input mode</option>
                                    <option value="tens" {{ 'tens' == old('input_mode', $currency->input_mode) ? 'selected' : '' }}>
                                        Tens
                                    </option>
                                    <option value="hundreds" {{ 'hundreds' == old('input_mode', $currency->input_mode) ? 'selected' : '' }}>
                                        Hundreds
                                    </option>
                                    <option value="thousands" {{ 'thousands' == old('input_mode', $currency->input_mode) ? 'selected' : '' }}>
                                        Thousands
                                    </option>
                                    <option value="millions" {{ 'millions' == old('input_mode', $currency->input_mode) ? 'selected' : '' }}>
                                        Millions
                                    </option>
                                </select>
                                <span class="text-danger" id="input_mode_error">
                                    @error('input_mode')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                        </div>
                    </div>
                    {{-- rate --}}
                    <div class="col-12 col-md-6 mt-3">
                        <div class="row">
                            <div class="col-3">
                                <label for="rate"
                                    class="add-form-label py-2  @error('rate') text-danger @enderror">{{ __('common.rate') }}</label>
                            </div>
                            <div class="col-9">
                                <input type="number" min="0"  step="0.000001"
                                    class="form-control  @error('rate') border-danger @enderror"
                                    value="{{ old('rate', $currency->rate) }}" id="rate" name="rate" placeholder="Enter rate">
                                <span class="text-danger" id="rate_error">
                                    @error('rate')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                        </div>
                    </div>
                    {{-- rounding_unit --}}
                    <div class="col-12 col-md-6 mt-3">
                        <div class="row">
                            <div class="col-3">
                                <label for="rounding_unit"
                                    class="add-form-label py-2  @error('rounding_unit') text-danger @enderror">
                                    {{ __('common.rounding_unit') }}</label>
                            </div>
                            <div class="col-9">
                                <input type="number" step="0.000001" min="0"
                                    class="form-control  @error('rounding_unit') border-danger @enderror"
                                    value="{{ old('rounding_unit', $currency->rounding_unit) }}" id="rounding_unit" name="rounding_unit"
                                    placeholder="Enter rounding_unit">
                                <span class="text-danger" id="rounding_unit_error">
                                    @error('rounding_unit')
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
