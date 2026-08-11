<x-main page="Settings: Payment Methods">
    <x-slot name="header">
        <x-header />
    </x-slot>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1 page-title">{{ __('common.add_payment_method') }}</h4>
                </div>
                <div>
                    <a href="{{ route('payment_methods.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left-square"></i>
                        {{ __('common.back') }}
                    </a>
                </div>
            </div>
            <hr class="thick-hr">
            <form action="{{ route('payment_methods.store') }}" class="w-100" method="POST"
                onsubmit="disableSubmitButton(this)">
                @csrf
                <div class="row">
                    {{-- name --}}
                    <div class="col-12 col-md-12 mt-3">
                        <div class="row">
                            <div class="col-3">
                                <label for="name"
                                    class="add-form-label py-2  @error('name') text-danger @enderror">{{ __('common.name') }}</label>
                            </div>
                            <div class="col-9">
                                <input type="text" class="form-control  @error('name') border-danger @enderror"
                                    value="{{ old('name') }}" id="name" name="name"
                                    placeholder="{{ __('common.enter_payment_method_name') }}">
                                <span class="text-danger" id="name_error">
                                    @error('name')
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
