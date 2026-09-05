<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Home') | HostelHub</title>
    <meta name="description" content="HostelHub — smart, well-managed hostel accommodation. Browse rooms, facilities, and get in touch.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,600;9..144,700&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@500;600&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/site.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg hh-navbar">
        <div class="container">
            <a class="navbar-brand hh-brand" href="{{ route('home') }}">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                    <circle cx="8" cy="8" r="5.25" stroke="#C79A46" stroke-width="1.8"/>
                    <path d="M11.7 11.7L20 20M20 20V15.5M20 20H15.5" stroke="#C79A46" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                HostelHub
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="nav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">About</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('rooms.index') ? 'active' : '' }}" href="{{ route('rooms.index') }}">Rooms &amp; Services</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('gallery.index') ? 'active' : '' }}" href="{{ route('gallery.index') }}">Gallery</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('contact.index') ? 'active' : '' }}" href="{{ route('contact.index') }}">Contact</a></li>
                </ul>
            </div>
        </div>
    </nav>

    @if(session('success'))
        <div class="container mt-3">
            <div class="alert alert-hh-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <main>
        @yield('content')
    </main>

    <footer class="hh-footer pt-5 pb-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <a href="{{ route('home') }}" class="hh-brand mb-2 d-inline-flex">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                            <circle cx="8" cy="8" r="5.25" stroke="#C79A46" stroke-width="1.8"/>
                            <path d="M11.7 11.7L20 20M20 20V15.5M20 20H15.5" stroke="#C79A46" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        HostelHub
                    </a>
                    <p class="small mb-0">A well-managed, secure place to stay — organized rooms, clear facilities, and a team that answers.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h6 class="eyebrow mb-3">Explore</h6>
                    <ul class="list-unstyled small">
                        <li class="mb-2"><a href="{{ route('rooms.index') }}">Rooms &amp; Services</a></li>
                        <li class="mb-2"><a href="{{ route('gallery.index') }}">Gallery</a></li>
                        <li class="mb-2"><a href="{{ route('contact.index') }}">Contact Us</a></li>
                        <li><a href="{{ route('admin.login') }}">Admin Login</a></li>
                    </ul>
                </div>
                <div class="col-md-4 mb-4">
                    <h6 class="eyebrow mb-3">Get in touch</h6>
                    <p class="small mb-0">Questions about a room or move-in date? <a href="{{ route('contact.index') }}">Send us a message</a> and we'll reply directly.</p>
                </div>
            </div>
            <hr>
            <p class="small text-center mb-0">&copy; {{ date('Y') }} HostelHub. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/site.js') }}"></script>
    @stack('scripts')
</body>
</html>
