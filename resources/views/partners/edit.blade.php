<x-main page="Partners">
    <x-slot name="header">
        <x-header />
    </x-slot>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1 page-title">
                        {{ __('partners.edit_partner') }}
                    </h4>
                </div>
                <div>
                    <a href="{{ route('partners.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left-square"></i>
                        {{ __('common.back') }}
                    </a>
                </div>
            </div>
            <hr class="thick-hr">
            <form action="{{ route('partners.update', $partner->id) }}"
                class="w-100"
                method="POST"
                onsubmit="disableSubmitButton(this)">
                @csrf
                @method('PUT')
                <div class="row">
                    {{-- Name --}}
                    <div class="col-12 col-md-6 mt-3">
                        <div class="row">
                            <div class="col-3">
                                <label for="name"
                                    class="add-form-label py-2 @error('name') text-danger @enderror">
                                    {{ __('common.name') }}
                                </label>
                            </div>
                            <div class="col-9">
                                <input type="text"
                                    class="form-control @error('name') border-danger @enderror"
                                    value="{{ old('name', $partner->name) }}"
                                    id="name"
                                    name="name"
                                    placeholder="{{ __('partners.enter_partner_name') }}">
                                <span class="text-danger">
                                    @error('name')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                        </div>
                    </div>
                    {{-- Phone --}}
                    <div class="col-12 col-md-6 mt-3">
                        <div class="row">
                            <div class="col-3">
                                <label for="phone"
                                    class="add-form-label py-2 @error('phone') text-danger @enderror">
                                    {{ __('common.phone') }}
                                </label>
                            </div>
                            <div class="col-9">
                                <input type="text"
                                    class="form-control @error('phone') border-danger @enderror"
                                    value="{{ old('phone', $partner->phone) }}"
                                    id="phone"
                                    name="phone"
                                    placeholder="{{ __('partners.enter_partner_phone') }}">
                                <span class="text-danger">
                                    @error('phone')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                        </div>
                    </div>
                    {{-- Fee Type --}}
                    <div class="col-12 col-md-6 mt-3">
                        <div class="row">
                            <div class="col-3">
                                <label for="fee_type"
                                    class="add-form-label py-2 @error('fee_type') text-danger @enderror">
                                    {{ __('partners.partner_type') }}
                                </label>
                            </div>
                            <div class="col-9">
                                <select class="form-select @error('fee_type') border-danger @enderror"
                                    id="fee_type"
                                    name="fee_type">
                                    <option value="">
                                        {{ __('partners.select_fee_type') }}
                                    </option>
                                    <option value="fixed"
                                        {{ old('fee_type', $partner->fee_type) == 'fixed' ? 'selected' : '' }}>
                                        {{ __('common.fixed') }}
                                    </option>
                                    <option value="percentage"
                                        {{ old('fee_type', $partner->fee_type) == 'percentage' ? 'selected' : '' }}>
                                        {{ __('common.percentage') }}
                                    </option>
                                </select>
                                <span class="text-danger">
                                    @error('fee_type')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                        </div>
                    </div>
                    {{-- Percentage --}}
                    <div class="col-12 col-md-6 mt-3">
                        <div class="row">
                            <div class="col-3">
                                <label for="percentage"
                                    class="add-form-label py-2 @error('percentage') text-danger @enderror">
                                    {{ __('partners.percentage_value') }}
                                </label>
                            </div>
                            <div class="col-9">
                                <input type="number"
                                    step="0.01"
                                    min="0"
                                    class="form-control @error('percentage') border-danger @enderror"
                                    value="{{ old('percentage', $partner->percentage) }}"
                                    id="percentage"
                                    name="percentage"
                                    placeholder="{{ __('partners.enter_percentage') }}">
                                <span class="text-danger">
                                    @error('percentage')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                        </div>
                    </div>
                    {{-- Fixed Fee --}}
                    <div class="col-12 col-md-6 mt-3">
                        <div class="row">
                            <div class="col-3">
                                <label for="fixed_fee"
                                    class="add-form-label py-2 @error('fixed_fee') text-danger @enderror">
                                    {{ __('partners.fixed_fee') }}
                                    ({{ $defaultCurrency->symbol }})
                                </label>
                            </div>
                            <div class="col-9">
                                <input type="number"
                                    step="0.01"
                                    min="0"
                                    class="form-control @error('fixed_fee') border-danger @enderror"
                                    value="{{ old('fixed_fee', $partner->fixed_fee) }}"
                                    id="fixed_fee"
                                    name="fixed_fee"
                                    placeholder="{{ __('partners.enter_fixed_fee') }}">
                                <span class="text-danger">
                                    @error('fixed_fee')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                        </div>
                    </div>
                    <hr class="mt-4">
                    {{-- Status --}}
                    <input type="hidden" name="is_active" value="0">
                    <div class="col-6">
                        <div class="form-check form-switch">
                            <label class="form-check-label" for="is_active">
                                {{ __('common.active') }}
                            </label>
                            <input class="form-check-input"
                                type="checkbox"
                                role="switch"
                                id="is_active"
                                name="is_active"
                                value="1"
                                {{ old('is_active', $partner->is_active) ? 'checked' : '' }}>
                        </div>
                    </div>
                    <div class="col-6 text-end">
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