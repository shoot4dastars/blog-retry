@extends('layouts.app')

@section('title', 'Change Password')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header bg-transparent border-0 pt-4 text-center">
                    <i class="bi bi-shield-lock-fill fs-1 text-warning"></i>
                    <h3 class="mt-2 fw-bold">Change Password</h3>
                    <p class="text-muted small">Update your account password</p>
                </div>
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success rounded-3">
                            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-key-fill"></i> Current Password
                            </label>
                            <input type="password" name="current_password" class="form-control" placeholder="Enter current password">
                            @error('current_password')
                            <div class="text-danger small mt-1"><i class="bi bi-exclamation-triangle-fill"></i> {{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-lock-fill"></i> New Password
                            </label>
                            <input type="password" name="password" class="form-control" placeholder="Min. 8 characters">
                            @error('password')
                            <div class="text-danger small mt-1"><i class="bi bi-exclamation-triangle-fill"></i> {{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-check-circle"></i> Confirm New Password
                            </label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat new password">
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-warning rounded-pill py-2 fw-semibold">
                                <i class="bi bi-save"></i> Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
