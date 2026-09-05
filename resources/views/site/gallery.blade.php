@extends('layouts.app')

@section('title', 'Gallery')

@section('content')
<section class="hh-hero" style="padding: clamp(2.5rem, 6vw, 4rem) 0;">
    <div class="container text-center">
        <span class="eyebrow d-inline-flex justify-content-center w-100">A look inside</span>
        <h1 style="font-size: clamp(2rem, 4vw, 2.9rem);">Gallery</h1>
    </div>
</section>

<section class="hh-section">
    <div class="container">
        <div class="row g-3 reveal">
            @forelse($images as $image)
                <div class="col-md-3 col-6">
                    <div class="hh-gallery-item">
                        <img src="{{ asset('storage/'.$image->image) }}" alt="{{ $image->title }}" loading="lazy">
                        @if($image->title)
                            <div class="hh-gallery-caption">{{ $image->title }}</div>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-muted text-center py-4">No images uploaded yet.</p>
            @endforelse
        </div>
        <div class="mt-4">{{ $images->links() }}</div>
    </div>
</section>
@endsection
