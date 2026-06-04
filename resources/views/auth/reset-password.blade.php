@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header bg-transparent border-0 pt-4 text-center">
                    <i class="bi bi-shield-lock-fill fs-1 text-danger"></i>
                    <h3 class="mt-2 fw-bold">Reset Password</h3>
                    <p class="text-muted small">Choose a new password for your account</p>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('password.store') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ request()->route('token') }}">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-envelope-fill"></i> Email
                            </label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', request()->email) }}" placeholder="your@email.com">
                            @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-lock-fill"></i> New Password
                            </label>
                            <input type="password" name="password" class="form-control" placeholder="Min. 8 characters">
                            @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-check-circle-fill"></i> Confirm New Password
                            </label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat new password">
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-danger rounded-pill py-2 fw-semibold">
                                <i class="bi bi-arrow-repeat"></i> Reset Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
