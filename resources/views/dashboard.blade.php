@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
            <h2 class="mb-0 fw-bold text-gradient" style="background: linear-gradient(135deg, #6366f1, #8b5cf6); -webkit-background-clip: text; background-clip: text; color: transparent;">
                Dashboard
            </h2>
            <div class="d-flex gap-2">
                <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary rounded-pill px-3">
                    <i class="bi bi-person-gear"></i> Edit Profile
                </a>
                <a href="{{ route('password.edit') }}" class="btn btn-outline-warning rounded-pill px-3">
                    <i class="bi bi-key"></i> Change Password
                </a>
            </div>
        </div>

        <div class="card border-0 shadow-lg rounded-4 mb-4 overflow-hidden">
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 rounded-3" style="background: #f1f5f9;">
                            <h5 class="text-muted mb-2"><i class="bi bi-person-fill"></i> Name</h5>
                            <h4 class="mb-0 fw-semibold">{{ $user->name }}</h4>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="p-3 rounded-3" style="background: #f1f5f9;">
                            <h5 class="text-muted mb-2"><i class="bi bi-envelope-fill"></i> Email</h5>
                            <h4 class="mb-0 fw-semibold">{{ $user->email }}</h4>
                        </div>
                    </div>
                </div>
                <hr class="my-4">
                <p class="text-muted mb-0">
                    <i class="bi bi-calendar-plus-fill"></i> Joined: {{ $user->created_at->format('d M Y') }}
                </p>
            </div>
        </div>

        @php
            $cardClass = auth()->user()->can('publish-posts')
                ? 'col-md-5'
                : 'col-md-4';
        @endphp
        <div class="row g-4 justify-content-center">
            <div class="{{ $cardClass }}">
                <div class="card border-0 shadow-sm rounded-4 text-center h-100 transition-all">
                    <div class="card-body p-4">
                        <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex p-3 mb-3">
                            <i class="bi bi-check-circle-fill fs-2 text-success"></i>
                        </div>
                        <h6 class="text-muted text-uppercase small fw-semibold">Published</h6>
                        <h2 class="display-5 fw-bold text-success mb-0">{{ $publishedCount }}</h2>
                    </div>
                </div>
            </div>
            <div class="{{ $cardClass }}">
                <div class="card border-0 shadow-sm rounded-4 text-center h-100">
                    <div class="card-body p-4">
                        <div class="rounded-circle bg-warning bg-opacity-10 d-inline-flex p-3 mb-3">
                            <i class="bi bi-file-earmark-text-fill fs-2 text-warning"></i>
                        </div>
                        <h6 class="text-muted text-uppercase small fw-semibold">Drafts</h6>
                        <h2 class="display-5 fw-bold text-warning mb-0">{{ $draftCount }}</h2>
                    </div>
                </div>
            </div>
            @cannot('publish-posts')
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 text-center h-100">
                    <div class="card-body p-4">
                        <div class="rounded-circle bg-info bg-opacity-10 d-inline-flex p-3 mb-3">
                            <i class="bi bi-hourglass-split fs-2 text-info"></i>
                        </div>
                        <h6 class="text-muted text-uppercase small fw-semibold">Pending</h6>
                        <h2 class="display-5 fw-bold text-info mb-0">{{ $pendingCount }}</h2>
                    </div>
                </div>
            </div>
            @endcannot
        </div>
    </div>
@endsection
