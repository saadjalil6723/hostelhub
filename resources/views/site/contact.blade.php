@extends('layouts.app')

@section('title', 'Contact')

@section('content')
<section class="hh-hero" style="padding: clamp(2.5rem, 6vw, 4rem) 0;">
    <div class="container text-center">
        <span class="eyebrow d-inline-flex justify-content-center w-100">Let's talk</span>
        <h1 style="font-size: clamp(2rem, 4vw, 2.9rem);">Contact Us</h1>
        <p class="lead-copy mx-auto">Ask about a room, a move-in date, or anything else — we reply directly, no ticket number required.</p>
    </div>
</section>

<section class="hh-section">
    <div class="container col-lg-6 reveal">
        @if(session('success'))
            <div class="alert alert-hh-success">{{ session('success') }}</div>
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

        <form method="POST" action="{{ route('contact.store') }}" class="hh-form">
            @csrf
            <div class="mb-3">
                <label class="form-label">Name *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Subject</label>
                <input type="text" name="subject" class="form-control" value="{{ old('subject') }}">
            </div>
            <div class="mb-3">
                <label class="form-label">Message *</label>
                <textarea name="message" rows="5" class="form-control" required>{{ old('message') }}</textarea>
            </div>
            <button type="submit" class="btn-brass w-100">Send Message</button>
        </form>
    </div>
</section>
@endsection
