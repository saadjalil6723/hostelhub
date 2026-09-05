@extends('layouts.admin')
@section('title', 'Message Details')
@section('content')
<h2 class="mb-4">Message from {{ $contact->name }}</h2>
<div class="card shadow-sm p-4">
    <table class="table table-borderless mb-3">
        <tr><th style="width:150px;">Name</th><td>{{ $contact->name }}</td></tr>
        <tr><th>Email</th><td>{{ $contact->email ?? '-' }}</td></tr>
        <tr><th>Phone</th><td>{{ $contact->phone ?? '-' }}</td></tr>
        <tr><th>Subject</th><td>{{ $contact->subject ?? '-' }}</td></tr>
        <tr><th>Received</th><td>{{ $contact->created_at->format('d M Y, h:i A') }}</td></tr>
    </table>
    <h6>Message</h6>
    <p class="border rounded p-3 bg-light">{{ $contact->message }}</p>

    <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" onsubmit="return confirm('Delete this message?');">
        @csrf @method('DELETE')
        <button class="btn btn-outline-danger">Delete Message</button>
    </form>
</div>
@endsection
