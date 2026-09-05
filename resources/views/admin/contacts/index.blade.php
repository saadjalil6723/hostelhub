@extends('layouts.admin')
@section('title', 'Contact Messages')
@section('content')
<h2 class="mb-4">Contact Messages</h2>
<div class="card shadow-sm">
    <table class="table table-hover mb-0">
        <thead><tr><th>Name</th><th>Subject</th><th>Received</th><th>Status</th><th></th></tr></thead>
        <tbody>
        @forelse($messages as $message)
            <tr class="{{ $message->status==='unread' ? 'fw-bold' : '' }}">
                <td><a href="{{ route('admin.contacts.show', $message) }}">{{ $message->name }}</a></td>
                <td>{{ $message->subject ?? '-' }}</td>
                <td>{{ $message->created_at->format('d M Y, h:i A') }}</td>
                <td><span class="badge bg-{{ $message->status==='unread'?'danger':'secondary' }}">{{ ucfirst($message->status) }}</span></td>
                <td class="text-end">
                    <form action="{{ route('admin.contacts.destroy', $message) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this message?');">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="5" class="text-center text-muted py-4">No messages yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="mt-3">{{ $messages->links() }}</div>
@endsection
