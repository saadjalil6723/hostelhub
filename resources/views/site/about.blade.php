@extends('layouts.app')

@section('title', 'About')

@section('content')
<section class="hh-hero" style="padding: clamp(2.5rem, 6vw, 4rem) 0;">
    <div class="container text-center">
        <span class="eyebrow d-inline-flex justify-content-center w-100">Who we are</span>
        <h1 style="font-size: clamp(2rem, 4vw, 2.9rem);">About HostelHub</h1>
    </div>
</section>

<section class="hh-section">
    <div class="container col-lg-8 reveal">
        <p class="fs-5">HostelHub runs on one idea: a place to live should never come with guesswork. Every room, resident, and rent payment is tracked the moment it changes — so "is anything open" always has a real, current answer.</p>

        <h4 class="mt-5 mb-3">What we focus on</h4>
        <ul class="list-unstyled">
            <li class="mb-3 d-flex gap-3"><span class="status-dot available flex-shrink-0 mt-2"></span><span>Safe, affordable accommodation with clear, upfront pricing.</span></li>
            <li class="mb-3 d-flex gap-3"><span class="status-dot available flex-shrink-0 mt-2"></span><span>Transparent, organized resident and room records — nothing lost, nothing double-booked.</span></li>
            <li class="mb-3 d-flex gap-3"><span class="status-dot available flex-shrink-0 mt-2"></span><span>Facilities and support that are actually responsive, not just listed on a page.</span></li>
        </ul>

        <h4 class="mt-5 mb-3">Who stays with us</h4>
        <p>Students and working professionals who want a dependable place to live, with straightforward access to the essentials — and a team that answers when something needs fixing.</p>
    </div>
</section>
@endsection
