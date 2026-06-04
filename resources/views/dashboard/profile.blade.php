@extends('layouts.app')

@section('title', 'Dashboard Profile')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card border-0 shadow-lg rounded-4">
                <div class="card-header bg-transparent border-0 pt-4 text-center">
                    <i class="bi bi-person-circle fs-1 text-primary"></i>
                    <h3 class="mt-2 fw-bold">Edit Profile</h3>
                    <p class="text-muted small">Update your personal information</p>
                </div>
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success rounded-3">
                            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-person-badge"></i> Name
                            </label>
                            <input type="text" name="name" class="form-control"
                                   value="{{ old('name', $user->name) }}" placeholder="Your full name">
                            @error('name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">
                                <i class="bi bi-envelope-fill"></i> Email Address
                            </label>
                            <input type="email" name="email" class="form-control"
                                   value="{{ old('email', $user->email) }}" placeholder="your@email.com">
                            @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary rounded-pill py-2 fw-semibold">
                                <i class="bi bi-check2-circle"></i> Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
