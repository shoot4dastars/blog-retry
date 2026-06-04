@extends('layouts.app')

@section('title', "Manage Permissions for {$user->name}")

@section('content')
    <div class="mb-4">
        <a href="{{ route('admin.users.permissions.index') }}" class="text-muted small text-decoration-none">
            ← Back to Users List
        </a>
        <h4 class="mt-2 mb-0">
            Permissions for <span class="text-info">{{ $user->name }}</span>
        </h4>
        <div class="mt-1">
            <span class="text-muted small">Roles assigned: </span>
            @forelse($user->roles as $role)
                <span class="badge bg-secondary text-capitalize">{{ $role->name }}</span>
            @empty
                <span class="badge bg-warning text-dark">No role</span>
            @endforelse
        </div>
        <p class="text-muted small mt-2 mb-0">
            <span class="badge bg-secondary me-1">via role</span> Inherited from role
        </p>
    </div>

    <div class="card border-0 shadow-lg rounded-4">
        <div class="card-header bg-transparent border-0 pt-4">
            <h5 class="mb-0 fw-semibold">Permission Overrides</h5>
            <p class="text-muted small mb-0">Choose <span class="text-success">Grant</span> to add extra, <span class="text-danger">Deny</span> to block inherited, or <span class="text-secondary">None</span> to keep role default.</p>
        </div>
        <div class="card-body p-0">
            <form method="POST" action="{{ route('admin.users.permissions.update', $user) }}" id="permissionForm">
                @csrf
                @method('PUT')

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                        <tr>
                            <th style="width: 40%">Permission</th>
                            <th class="text-center" style="width: 20%">Grant</th>
                            <th class="text-center" style="width: 20%">Deny</th>
                            <th class="text-center" style="width: 20%">None</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($permissions as $permission)
                            @php
                                $fromRole = $rolePerms->contains($permission->id);
                                $isDirect = $directPerms->contains($permission->id);
                                $isDenied = $deniedPerms->contains($permission->id);
                                $isInherited = $fromRole && !$isDirect && !$isDenied;
                                if ($isDirect) {
                                    $selected = 'grant';
                                } elseif ($isDenied) {
                                    $selected = 'deny';
                                } elseif ($isInherited) {
                                    $selected = 'grant';
                                } else {
                                    $selected = 'none';
                                }
                            @endphp
                            <tr>
                                <td>
                                    <span class="fw-semibold">{{ $permission->name }}</span>
                                    @if($permission->route_name)
                                        <div class="text-muted small">{{ $permission->route_name }}</div>
                                    @endif
                                    @if($isInherited)
                                        <span class="badge bg-secondary ms-2">via role</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="form-check d-flex justify-content-center">
                                        <input class="form-check-input grant-radio"
                                               type="radio"
                                               name="perm_{{ $permission->id }}"
                                               value="grant"
                                               id="grant_{{ $permission->id }}"
                                               @if($selected === 'grant') checked @endif
                                               @if($isInherited) disabled @endif>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="form-check d-flex justify-content-center">
                                        <input class="form-check-input deny-radio"
                                               type="radio"
                                               name="perm_{{ $permission->id }}"
                                               value="deny"
                                               id="deny_{{ $permission->id }}"
                                               @if($selected === 'deny') checked @endif>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="form-check d-flex justify-content-center">
                                        <input class="form-check-input none-radio"
                                               type="radio"
                                               name="perm_{{ $permission->id }}"
                                               value="none"
                                               id="none_{{ $permission->id }}"
                                               @if($selected === 'none') checked @endif>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="card-footer bg-transparent border-0 pt-3 pb-4">
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <i class="bi bi-save"></i> Save Permission Overrides
                    </button>
                    <span class="text-muted small ms-3">
                        <i class="bi bi-info-circle"></i> "None" removes any direct override (use role default)
                    </span>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('permissionForm').addEventListener('submit', function(e) {
            const existingGrants = document.querySelectorAll('input[name="permissions[]"]');
            const existingDenies = document.querySelectorAll('input[name="denied_permissions[]"]');
            existingGrants.forEach(el => el.remove());
            existingDenies.forEach(el => el.remove());

            const allRadios = document.querySelectorAll('input[type="radio"][name^="perm_"]');
            const permissionIds = new Set();
            allRadios.forEach(radio => {
                const match = radio.name.match(/perm_(\d+)/);
                if (match) permissionIds.add(match[1]);
            });

            permissionIds.forEach(permId => {
                const groupRadios = document.querySelectorAll(`input[name="perm_${permId}"]:not(:disabled)`);
                let selectedRadio = null;
                for (let radio of groupRadios) {
                    if (radio.checked) {
                        selectedRadio = radio;
                        break;
                    }
                }
                if (selectedRadio) {
                    const value = selectedRadio.value;
                    if (value === 'grant') {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'permissions[]';
                        input.value = permId;
                        this.appendChild(input);
                    } else if (value === 'deny') {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'denied_permissions[]';
                        input.value = permId;
                        this.appendChild(input);
                    }
                }
            });
        });
    </script>
@endsection
