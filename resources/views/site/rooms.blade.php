@extends('layouts.app')

@section('title', 'Rooms & Services')

@section('content')
<section class="hh-hero" style="padding: clamp(2.5rem, 6vw, 4rem) 0;">
    <div class="container text-center">
        <span class="eyebrow d-inline-flex justify-content-center w-100">What's available</span>
        <h1 style="font-size: clamp(2rem, 4vw, 2.9rem);">Rooms &amp; Services</h1>
        <p class="lead-copy mx-auto">Every room below reflects current occupancy — no waiting for a callback to find out what's open.</p>
    </div>
</section>

<section class="hh-section">
    <div class="container">
        <div class="section-heading reveal">
            <span class="eyebrow">Accommodation</span>
            <h2>Rooms</h2>
        </div>
        <div class="row g-4 mb-5">
            @forelse($rooms as $room)
                <div class="col-md-4 reveal">
                    <div class="hh-card hh-room-card status-{{ $room->status }}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0">Room {{ $room->room_number }}</h5>
                                <span class="hh-badge status-{{ $room->status }}">{{ str_replace('_',' ', ucfirst($room->status)) }}</span>
                            </div>
                            <p class="text-muted small mb-1">{{ $room->room_type }} &middot; Floor {{ $room->floor ?? 'N/A' }} &middot; Capacity {{ $room->capacity }}</p>
                            @if($room->facilities)
                                <p class="small mb-2">{{ $room->facilities }}</p>
                            @endif
                            <p class="room-price mb-0">Rs. {{ number_format($room->price, 0) }} <span class="text-muted fw-normal small">/ month</span></p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-muted text-center py-4">No rooms are listed yet — check back soon, or <a href="{{ route('contact.index') }}">ask us directly</a>.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>

<section class="hh-section hh-section-alt">
    <div class="container">
        <div class="section-heading reveal">
            <span class="eyebrow">On-site</span>
            <h2>Services &amp; Facilities</h2>
        </div>
        <div class="row g-4">
            @forelse($services as $service)
                <div class="col-md-4 reveal">
                    <div class="hh-card">
                        @if($service->image)
                            <img src="{{ asset('storage/'.$service->image) }}" class="card-img-top" alt="{{ $service->title }}">
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $service->title }}</h5>
                            <p class="card-text text-muted small mb-0">{{ $service->description }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-muted text-center py-4">No services listed yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
