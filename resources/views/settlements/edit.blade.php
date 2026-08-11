<x-main page="Settlements">
    <x-slot name="header">
        <x-header />
    </x-slot>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1 page-title">{{ __('common.edit_settlement') }}</h4>
                </div>
                <div>
                    <a href="{{ route('settlements.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left-square"></i>
                        {{ __('common.back') }}
                    </a>
                </div>
            </div>
            <hr class="thick-hr">
            <form action="{{ route('settlements.update', $settlement->id) }}" class="w-100" method="POST"
                onsubmit="disableSubmitButton(this)">
                @csrf
                @method('PUT')
                {{-- notes --}}
                <div class="row">
                    {{-- date --}}
                    <div class="col-12 col-md-12 mt-3">
                        <div class="row">
                            <div class="col-3">
                                <label for="date"class="add-form-label py-2  @error('date') text-danger @enderror">
                                    {{ __('common.date') }}
                                </label>
                            </div>
                            <div class="col-12">
                                <input type="date" class="form-control  @error('date') border-danger @enderror"
                                    value="{{ old('date', $settlement->date) }}" id="date" name="date"
                                    placeholder="Enter driver amount">
                                <span class="text-danger" id="date_error">
                                    @error('date')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-12 mt-3">
                        <div class="row mt-1">
                            <div class="col-md-1 col-3">
                                <label for="notes" class="add-form-label py-2 @error('notes') text-danger @enderror">
                                    {{ __('common.note') }}
                                </label>
                            </div>
                            <div class="col-md-12 col-9 d-flex">
                                <textarea name="notes" class="form-control me-1 @error('notes') border-danger @enderror" id="notes">{{ old('notes', $settlement->notes) }}</textarea>
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
