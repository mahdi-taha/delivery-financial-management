<x-main page="Drivers">
    <x-slot name="header">
        <x-header />
    </x-slot>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1 page-title">{{ __('drivers.add_driver') }}</h4>
                </div>
                <div>
                    <a href="{{ route('drivers.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left-square"></i>
                        {{ __('common.back') }}
                    </a>
                </div>
            </div>
            <hr class="thick-hr">
            <form action="{{ route('drivers.store') }}" class="w-100" method="POST"
                onsubmit="disableSubmitButton(this)">
                @csrf
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
                                    value="{{ old('name') }}" id="name" name="name"
                                    placeholder="{{ __('drivers.driver_name') }}">
                                <span class="text-danger" id="name_error">
                                    @error('name')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                        </div>
                    </div>
                    {{-- phone --}}
                    <div class="col-12 col-md-6 mt-3">
                        <div class="row">
                            <div class="col-3">
                                <label for="phone"
                                    class="add-form-label py-2  @error('phone') text-danger @enderror">{{ __('common.phone') }}</label>
                            </div>
                            <div class="col-9">
                                <input type="text" class="form-control  @error('phone') border-danger @enderror"
                                    value="{{ old('phone') }}" id="phone" name="phone"
                                    placeholder="{{ __('drivers.driver_phone') }}">
                                <span class="text-danger" id="phone_error">
                                    @error('phone')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                        </div>
                    </div>
                    {{-- type --}}
                    <div class="col-12 col-md-6 mt-3">
                        <div class="row">
                            <div class="col-3">
                                <label for="driver_type"
                                    class="add-form-label py-2 @error('driver_type') text-danger @enderror">
                                    {{ __('drivers.driver_type') }}
                                    </label>
                            </div>
                            <div class="col-9">
                                <select class="form-select @error('driver_type') border-danger @enderror"
                                    id="driver_type" name="driver_type">
                                    <option value="">{{ __('drivers.select_driver_type') }}</option>
                                    <option value="fixed" {{ 'fixed' == old('driver_type') ? 'selected' : '' }}>
                                        {{ __('common.fixed') }}
                                    </option>
                                    <option value="percentage"
                                        {{ 'percentage' == old('driver_type') ? 'selected' : '' }}>
                                        {{ __('common.percentage') }}
                                    </option>
                                    <option value="custom" {{ 'custom' == old('driver_type') ? 'selected' : '' }}>
                                        {{ __('drivers.custom') }}
                                    </option>
                                </select>
                                <span class="text-danger" id="driver_type_error">
                                    @error('driver_type')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                        </div>
                    </div>
                    {{-- percentage --}}
                    <div class="col-12 col-md-6 mt-3">
                        <div class="row">
                            <div class="col-3">
                                <label for="percentage"
                                    class="add-form-label py-2  @error('percentage') text-danger @enderror">{{ __('common.percentage') }}
                                    (%)</label>
                            </div>
                            <div class="col-9">
                                <input type="number" step="0.01" min="0"
                                    class="form-control  @error('percentage') border-danger @enderror"
                                    value="{{ old('percentage') }}" id="percentage" name="percentage"
                                    placeholder="{{ __('drivers.driver_percentage') }}">
                                <span class="text-danger" id="percentage_error">
                                    @error('percentage')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                        </div>
                    </div>
                    {{-- salary --}}
                    <div class="col-12 col-md-6 mt-3">
                        <div class="row">
                            <div class="col-3">
                                <label
                                    for="salary"class="add-form-label py-2  @error('salary') text-danger @enderror">{{ __('common.salary') }}
                                    ({{ $defaultCurrency->symbol }})
                                </label>
                            </div>
                            <div class="col-9">
                                <input type="number" step="0.01" min="0"
                                    class="form-control  @error('salary') border-danger @enderror"
                                    value="{{ old('salary') }}" id="salary" name="salary">
                                <span class="text-danger" id="salary_error">
                                    @error('salary')
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
                            <div class="col-md-12 col-9 d-flex">
                                <textarea name="notes" class="form-control me-1 @error('notes') border-danger @enderror"
                                    id="notes">{{ old('notes') }}</textarea>
                            </div>
                            <div class="col-md-1 col-3"></div>
                            <div class="col-md-11 col-9 d-flex">
                                <span class="text-danger" id="notes_error">
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
                                <i class="bi bi-floppy ms-2 me-2"></i>
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