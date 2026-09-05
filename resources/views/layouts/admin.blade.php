<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') | HostelHub Admin</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@500;600&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
<div class="d-flex">
    <div class="hh-admin-sidebar">
        <a href="{{ route('admin.dashboard') }}" class="hh-admin-brand">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <circle cx="8" cy="8" r="5.25" stroke="#C79A46" stroke-width="1.8"/>
                <path d="M11.7 11.7L20 20M20 20V15.5M20 20H15.5" stroke="#C79A46" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            HostelHub Admin
        </a>
        <nav class="d-flex flex-column">
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">Dashboard</a>
            <a href="{{ route('admin.rooms.index') }}" class="{{ request()->routeIs('admin.rooms.*') ? 'active' : '' }}">Rooms</a>
            <a href="{{ route('admin.residents.index') }}" class="{{ request()->routeIs('admin.residents.*') ? 'active' : '' }}">Residents</a>
            <a href="{{ route('admin.allocations.index') }}" class="{{ request()->routeIs('admin.allocations.*') ? 'active' : '' }}">Room Allocations</a>
            <a href="{{ route('admin.payments.index') }}" class="{{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">Payments</a>
            <a href="{{ route('admin.services.index') }}" class="{{ request()->routeIs('admin.services.*') ? 'active' : '' }}">Services</a>
            <a href="{{ route('admin.gallery.index') }}" class="{{ request()->routeIs('admin.gallery.*') ? 'active' : '' }}">Gallery</a>
            <a href="{{ route('admin.contacts.index') }}" class="{{ request()->routeIs('admin.contacts.*') ? 'active' : '' }}">Contact Messages</a>
            <a href="{{ route('admin.profile.edit') }}" class="{{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">My Profile</a>
        </nav>
        <form action="{{ route('admin.logout') }}" method="POST" class="mt-4">
            @csrf
            <button class="btn btn-outline-light btn-sm w-100">Logout</button>
        </form>
    </div>

    <div class="flex-grow-1">
        <div class="hh-admin-topbar d-flex justify-content-between align-items-center">
            <span>@yield('title', 'Dashboard')</span>
            <span>Signed in as <strong>{{ auth('admin')->user()->name }}</strong></span>
        </div>

        <div class="container-fluid p-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
