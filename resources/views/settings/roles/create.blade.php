<x-main page="Settings: Roles Add">
    <x-slot name="header">
        <x-header />
    </x-slot>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="page-title mb-1">
                        {{ __('common.add_role') }}
                    </h4>
                    <p class="text-muted mb-0">
                        {{ __('common.create_new_role_for_users') }}
                    </p>
                </div>
                <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left-square"></i>
                    {{ __('common.back') }}
                </a>
            </div>
            <hr class="thick-hr">
            <form action="{{ route('roles.store') }}" method="POST" onsubmit="disableSubmitButton(this)"
                enctype="multipart/form-data">
                @csrf
                <div class="row">
                    {{-- name --}}
                    <div class="col-12 mt-3">
                        <div class="row">
                            <div class="col-3">
                                <label class="add-form-label py-2 @error('name') text-danger @enderror">
                                    {{ __('common.role') }}
                                </label>
                            </div>
                            <div class="col-9">
                                <input type="text" name="name"
                                    class="form-control @error('name') border-danger @enderror"
                                    value="{{ old('name') }}">
                                @error('name')
                                    <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <hr class="mt-4">
                    {{-- submit --}}
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