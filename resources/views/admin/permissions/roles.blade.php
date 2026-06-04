@extends('layouts.app')

@section('title', 'Role Permissions')

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h4 class="mb-1 fw-bold">Role Permissions</h4>
            <p class="text-muted small mb-0">Assign permissions to each role. Users inherit all permissions of their assigned role(s).</p>
        </div>
        <a href="{{ route('admin.users.permissions.index') }}" class="btn btn-outline-secondary rounded-pill btn-sm px-3">
            👤 Per-user overrides →
        </a>
    </div>

    @foreach($roles as $role)
        <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <span class="fw-bold text-capitalize fs-5">{{ $role->name }} Role</span>
                <span class="badge bg-primary rounded-pill px-3">{{ $role->permissions->count() }} / {{ $permissions->count() }}</span>
            </div>

            <form method="POST" action="{{ route('admin.roles.permissions.update', $role) }}">
                @csrf
                @method('PUT')

                <div class="card-body pt-0">
                    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                        @foreach($permissions as $permission)
                            <div class="col">
                                <div class="form-check border rounded-3 p-3 ps-4 h-100 bg-light">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           name="permissions[]"
                                           value="{{ $permission->id }}"
                                           id="role_{{ $role->id }}_perm_{{ $permission->id }}"
                                           @if($role->permissions->contains($permission)) checked @endif>
                                    <label class="form-check-label w-100" for="role_{{ $role->id }}_perm_{{ $permission->id }}">
                                        <span class="d-block fw-semibold">{{ $permission->name }}</span>
                                        @if($permission->route_name)
                                            <span class="text-muted" style="font-size: 0.75rem;">{{ $permission->route_name }}</span>
                                        @endif
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="card-footer bg-white border-0 pt-0 pb-4">
                    <button type="submit" class="btn btn-primary rounded-pill px-4 btn-sm">
                        <i class="bi bi-save"></i> Save {{ ucfirst($role->name) }} Permissions
                    </button>
                </div>
            </form>
        </div>
    @endforeach
@endsection
