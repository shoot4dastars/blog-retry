@extends('layouts.app')

@section('title', 'User Permissions')

@section('content')
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h4 class="mb-1 fw-bold">User Permissions</h4>
            <p class="text-muted small mb-0">Select a user to manage their direct permission overrides.</p>
        </div>
        <a href="{{ route('admin.roles.permissions.index') }}" class="btn btn-outline-secondary rounded-pill btn-sm px-3">
            Manage by Role →
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                    <tr>
                        <th class="ps-4">Name</th>
                        <th>Email</th>
                        <th>Direct Permissions</th>
                        <th class="pe-4 text-end">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td class="ps-4 fw-semibold">{{ $user->name }}</td>
                            <td class="text-muted">{{ $user->email }}</td>
                            <td>
                                <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-3 py-1">
                                    {{ $user->permissions->count() }}
                                </span>
                            </td>
                            <td class="pe-4 text-end">
                                <a href="{{ route('admin.users.permissions.edit', $user) }}"
                                   class="btn btn-outline-primary rounded-pill btn-sm px-3">
                                    <i class="bi bi-pencil"></i> Manage
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @if($users->hasPages())
            <div class="card-footer bg-white border-0 py-3">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection
