<x-main page="Settings: Users Edit">
    <x-slot name="header">
        <x-header />
    </x-slot>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="mb-1 page-title">
                        {{ __('common.edit_user') }}
                    </h4>
                </div>
                <div>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left-square"></i>
                        {{ __('common.back') }}
                    </a>
                </div>
            </div>
            <hr class="thick-hr">
            <form action="{{ route('users.update', $user->id) }}" class="w-100" method="POST"
                onsubmit="disableSubmitButton(this)">
                @csrf
                @method('PUT')
                <div class="row">
                    {{-- Name --}}
                    <div class="col-12 col-md-6 mt-3">
                        <div class="row">
                            <div class="col-3">
                                <label class="add-form-label py-2 @error('name') text-danger @enderror">
                                    {{ __('common.name') }}
                                </label>
                            </div>
                            <div class="col-9">
                                <input type="text" name="name"
                                    class="form-control @error('name') border-danger @enderror"
                                    value="{{ old('name', $user->name) }}">
                                @error('name')
                                    <span class="text-danger">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    {{-- Username --}}
                    <div class="col-12 col-md-6 mt-3">
                        <div class="row">
                            <div class="col-3">
                                <label for="username"
                                    class="add-form-label py-2 @error('username') text-danger @enderror">
                                    {{ __('common.username') }}
                                </label>
                            </div>
                            <div class="col-9">
                                <input type="text"
                                    class="form-control @error('username') border-danger @enderror"
                                    value="{{ old('username', $user->username) }}"
                                    id="username"
                                    name="username">
                                <span class="text-danger">
                                    @error('username')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                        </div>
                    </div>
                    {{-- Password --}}
                    <div class="col-12 col-md-6 mt-3">
                        <div class="row">
                            <div class="col-3">
                                <label for="password"
                                    class="add-form-label py-2 @error('password') text-danger @enderror">
                                    {{ __('common.password') }}
                                </label>
                            </div>
                            <div class="col-9">
                                <div class="input-group">
                                    <input type="password"
                                        class="form-control @error('password') border-danger @enderror"
                                        value="{{ old('password') }}"
                                        id="password"
                                        name="password">
                                    <button class="btn btn-light border" type="button" onclick="togglePassword()">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </div>
                                <small class="text-muted">
                                    {{ __('common.leave_empty_keep_password') }}
                                </small>
                                <span class="text-danger">
                                    @error('password')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                        </div>
                    </div>
                    {{-- Role --}}
                    <div class="col-12 col-md-6 mt-3">
                        <div class="row">
                            <div class="col-3">
                                <label for="role"
                                    class="add-form-label py-2 @error('role') text-danger @enderror">
                                    {{ __('common.role') }}
                                </label>
                            </div>
                            <div class="col-9 @error('role') select2-danger @enderror">
                                <select name="role" class="form-control select2" style="width:100%" id="role">
                                    <option value="">
                                        {{ __('common.select_role') }}
                                    </option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id }}"
                                            {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span class="text-danger">
                                    @error('role')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                        </div>
                    </div>
                    {{-- Salary --}}
                    <div class="col-12 col-md-6 mt-3">
                        <div class="row">
                            <div class="col-3">
                                <label for="salary"
                                    class="add-form-label py-2 @error('salary') text-danger @enderror">
                                    {{ __('common.salary') }}
                                    ({{ $defaultCurrency->symbol }})
                                </label>
                            </div>
                            <div class="col-9">
                                <input type="number"
                                    min="0"
                                    step="0.01"
                                    class="form-control @error('salary') border-danger @enderror"
                                    value="{{ old('salary', $user->salary) }}"
                                    id="salary"
                                    name="salary"
                                    placeholder="{{ __('common.enter_salary') }}">
                                <span class="text-danger">
                                    @error('salary')
                                        {{ $message }}
                                    @enderror
                                </span>
                            </div>
                        </div>
                    </div>
                    <hr class="mt-4">
                    <input type="hidden" name="is_active" value="0">
                    {{-- Status --}}
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
                                {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                        </div>
                    </div>
                    <div class="col-6 text-end">
                        <button type="submit" class="btn btn-main shadow">
                            <span class="btn-text">
                                <i class="bi bi-floppy me-2"></i>
                                {{ __('common.save') }}
                            </span>
                            <span class="btn-loading d-none">
                                {{ __('common.saving') }}
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    </script>
</x-main>