@extends('layouts.app')

@section('title', 'Home')

@section('content')
<section class="hh-hero">
    <div class="container hh-hero-grid">
        <div class="hh-hero-copy">
            <span class="eyebrow">Move-in ready, room by room</span>
            <h1>A hostel that keeps every key accounted for.</h1>
            <p class="lead-copy">HostelHub tracks every room, resident, and rent payment in one place — so when you ask "which rooms are open right now," there's a real answer, not a guess.</p>
            <div class="hh-hero-actions">
                <a href="{{ route('rooms.index') }}" class="btn-brass">Browse Rooms &amp; Services</a>
                <a href="{{ route('contact.index') }}" class="btn-outline-paper">Ask a Question</a>
            </div>
        </div>

        <div class="keywall" role="img" aria-label="Wall of room key tags showing live availability">
            @forelse($rooms as $room)
                <div class="key-tag">
                    <span class="room-no">{{ $room->room_number }}</span>
                    <span class="room-type-mini">{{ \Illuminate\Support\Str::limit($room->room_type, 12) }}</span><br>
                    <span class="status-dot {{ $room->status }}" title="{{ str_replace('_',' ', ucfirst($room->status)) }}"></span>
                </div>
            @empty
                @foreach(['A1','A2','B1','B2','C1','C2','D1','D2'] as $tag)
                    <div class="key-tag" aria-hidden="true">
                        <span class="room-no">{{ $tag }}</span>
                        <span class="room-type-mini">Room</span><br>
                        <span class="status-dot available"></span>
                    </div>
                @endforeach
            @endforelse
        </div>
    </div>
</section>

<section class="hh-section">
    <div class="container">
        <div class="section-heading text-center mx-auto reveal" style="max-width: 640px;">
            <span class="eyebrow d-inline-flex justify-content-center w-100">Why HostelHub</span>
            <h2>Everything about your stay, kept in order.</h2>
            <p class="mx-auto">No overbooked rooms, no lost paperwork. Every allocation and payment is logged the moment it happens.</p>
        </div>
    </div>
</section>

@if($services->count())
<section class="hh-section hh-section-alt">
    <div class="container">
        <div class="section-heading reveal">
            <span class="eyebrow">On-site</span>
            <h2>Facilities &amp; services</h2>
            <p>The essentials that make a hostel feel like a place to actually live.</p>
        </div>
        <div class="row g-4">
            @foreach($services as $service)
                <div class="col-md-4 reveal">
                    <div class="hh-card">
                        @if($service->image)
                            <img src="{{ asset('storage/'.$service->image) }}" class="card-img-top" alt="{{ $service->title }}">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $service->title }}</h5>
                            <p class="card-text text-muted small mb-0">{{ \Illuminate\Support\Str::limit($service->description, 100) }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@if($rooms->count())
<section class="hh-section">
    <div class="container">
        <div class="section-heading reveal">
            <span class="eyebrow">Live availability</span>
            <h2>Featured rooms</h2>
            <p>A snapshot of what's open right now — the full list is always current on the Rooms page.</p>
        </div>
        <div class="row g-4">
            @foreach($rooms->take(3) as $room)
                <div class="col-md-4 reveal">
                    <div class="hh-card hh-room-card status-{{ $room->status }}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0">Room {{ $room->room_number }}</h5>
                                <span class="hh-badge status-{{ $room->status }}">{{ str_replace('_',' ', ucfirst($room->status)) }}</span>
                            </div>
                            <p class="text-muted small mb-2">{{ $room->room_type }} &middot; Capacity {{ $room->capacity }}</p>
                            <p class="room-price mb-0">Rs. {{ number_format($room->price, 0) }} <span class="text-muted fw-normal small">/ month</span></p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-center mt-4 reveal">
            <a href="{{ route('rooms.index') }}" class="btn-outline-ink">See all rooms</a>
        </div>
    </div>
</section>
@endif

@if($galleryPreview->count())
<section class="hh-section hh-section-alt">
    <div class="container">
        <div class="section-heading reveal">
            <span class="eyebrow">A look inside</span>
            <h2>Gallery</h2>
        </div>
        <div class="row g-3 reveal">
            @foreach($galleryPreview as $image)
                <div class="col-md-2 col-4">
                    <div class="hh-gallery-item">
                        <img src="{{ asset('storage/'.$image->image) }}" alt="{{ $image->title }}" loading="lazy">
                        @if($image->title)
                            <div class="hh-gallery-caption">{{ $image->title }}</div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-center mt-4 reveal">
            <a href="{{ route('gallery.index') }}" class="btn-outline-ink">View full gallery</a>
        </div>
    </div>
</section>
@endif

<section class="hh-section">
    <div class="container text-center reveal">
        <span class="eyebrow d-inline-flex justify-content-center w-100">Get in touch</span>
        <h2 class="mt-2 mb-3">Still deciding? Ask us anything.</h2>
        <a href="{{ route('contact.index') }}" class="btn-brass">Contact Us</a>
    </div>
</section>
@endsection
