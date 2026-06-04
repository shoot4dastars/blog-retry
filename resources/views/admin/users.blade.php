@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')
    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
        <div class="card-header bg-white border-0 pt-4 pb-2">
            <h4 class="mb-0 fw-bold">Manage Users</h4>
            <p class="text-muted small mt-1">Activate or suspend user accounts.</p>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                    <tr>
                        <th class="ps-4">Name</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th class="pe-4 text-end">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td class="ps-4 fw-semibold">{{ $user->name }}</td>
                            <td class="text-muted">{{ $user->email }}</td>
                            <td>
                                @if($user->is_active)
                                    <span class="badge bg-success rounded-pill px-3 py-1">
                                        <i class="bi bi-check-circle-fill me-1"></i> Active
                                    </span>
                                @else
                                    <span class="badge bg-danger rounded-pill px-3 py-1">
                                        <i class="bi bi-exclamation-circle-fill me-1"></i> Suspended
                                    </span>
                                @endif
                            </td>
                            <td class="pe-4 text-end">
                                <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    @if($user->is_active)
                                        <button type="submit" class="btn btn-outline-danger rounded-pill btn-sm px-3">
                                            <i class="bi bi-ban"></i> Suspend
                                        </button>
                                    @else
                                        <button type="submit" class="btn btn-outline-success rounded-pill btn-sm px-3">
                                            <i class="bi bi-check2-circle"></i> Activate
                                        </button>
                                    @endif
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="bi bi-people fs-1"></i>
                                <p class="mt-2 mb-0">No users found.</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
