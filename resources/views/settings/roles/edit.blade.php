<x-main page="Settings: Roles Edit">
    <x-slot name="header">
        <x-header />
    </x-slot>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="page-title mb-1">
                        {{ __('common.edit_role') }}
                    </h4>
                </div>
                <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left-square"></i>
                    {{ __('common.back') }}
                </a>
            </div>
            <hr class="thick-hr">
            <form action="{{ route('roles.update', $role) }}" method="POST" onsubmit="disableSubmitButton(this)">
                @csrf
                @method('PUT')
                <div class="mb-4">
                    <label class="form-label fw-bold @error('name') text-danger @enderror"">
                        {{ __('common.name') }}
                    </label>
                    <input type="text" name="name" class="form-control @error('name') border-danger @enderror"
                        value="{{ old('name', $role->name) }}">
                    @error('name')
                        <span class="text-danger">
                            {{ $message }}
                        </span>
                    @enderror
                </div>
                <h5 class="mb-3">
                    {{ __('common.permissions') }}
                </h5>
                @foreach ($permissions as $group => $groupPermissions)
                    <div class="permission-group mb-4">
                        <div class="permission-header">
                            <div class="form-check m-0">
                                <input type="checkbox" class="form-check-input group-checkbox"
                                    id="group_{{ Str::slug($group) }}">
                                <label class="form-check-label fw-bold" for="group_{{ Str::slug($group) }}">
                                    {{ __('permissions.groups.' . $group) }}
                                </label>
                            </div>
                            <span class="badge bg-light text-dark">
                                {{ count($groupPermissions) }}
                            </span>
                        </div>
                        <div class="permission-body">
                            @foreach ($groupPermissions as $permission)
                                <div class="permission-item">
                                    <input class="form-check-input permission-checkbox" type="checkbox"
                                        name="permissions[]" value="{{ $permission->id }}"
                                        id="permission_{{ $permission->id }}" data-group="{{ Str::slug($group) }}"
                                        {{ $role->permissions->contains($permission->id) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="permission_{{ $permission->id }}">
                                        {{ __('permissions.actions.' . Str::after($permission->name, '.')) }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
                <div class="text-end">
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
            </form>
        </div>
    </div>
    <script>
        document.querySelectorAll('.group-checkbox').forEach(groupCheckbox => {
            groupCheckbox.addEventListener('change', function() {
                const group = this.id.replace('group_', '');
                document
                    .querySelectorAll(
                        `.permission-checkbox[data-group="${group}"]`
                    )
                    .forEach(permission => {
                        permission.checked = this.checked;
                    });
            });
        });
    </script>
</x-main>
