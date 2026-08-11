<x-main page="Settings: Company Information">
    <x-slot name="header">
        <x-header />
    </x-slot>
    <div class="container-fluid">
        <form action="{{ route('company_info.update', $company->id ?? 1) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="page-title mb-0"> {{ __('common.company_info') }}</h4>
                        <button type="submit" class="btn btn-main">
                            <i class="bi bi-check-circle"></i>
                             {{ __('common.save') }}
                        </button>
                    </div>
                    <hr class="thick-hr">
                    <div class="row">
                        {{-- Logo --}}
                        <div class="col-md-3 text-center">
                            @if ($company?->logo)
                                <img src="{{ asset('storage/' . $company->logo) }}" id="logoPreview"
                                    class="img-fluid rounded border shadow-sm mb-3"
                                    style="max-width:180px;max-height:180px;">
                            @else
                                <img src="{{ asset('images/nologo.jpg') }}" id="logoPreview"
                                    class="img-fluid rounded border shadow-sm mb-3"
                                    style="max-width:180px;max-height:180px;">
                            @endif
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="logo" name="logo"
                                    accept="image/*">
                            </div>
                            @error('logo')
                                <small class="text-danger d-block mt-2">
                                    {{ $message }}
                                </small>
                            @enderror
                        </div>
                        {{-- Information --}}
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label> {{ __('common.company_name') }}</label>
                                    <input type="text" name="name"
                                        class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', $company?->name) }}">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label> {{ __('common.phone') }}</label>
                                    <input type="text" name="phone"
                                        class="form-control @error('phone') is-invalid @enderror"
                                        value="{{ old('phone', $company?->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-12 mb-3">
                                    <label> {{ __('common.address') }}</label>
                                    <input type="text" name="address"
                                        class="form-control @error('address') is-invalid @enderror"
                                        value="{{ old('address', $company?->address) }}">
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label> {{ __('common.email') }}</label>
                                    <input type="email" name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email', $company?->email) }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label> {{ __('common.website') }}</label>
                                    <input type="text" name="website"
                                        class="form-control @error('website') is-invalid @enderror"
                                        value="{{ old('website', $company?->website) }}">
                                    @error('website')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</x-main>
<script>
    $('#logo').on('change', function() {
        const file = this.files[0];
        if (!file) return;
        $('.custom-file-label').text(file.name);
        const reader = new FileReader();
        reader.onload = function(e) {
            $('#logoPreview').attr('src', e.target.result);
        };
        reader.readAsDataURL(file);
    });
</script>
