@extends('layouts.admin')
@section('title', 'Gallery')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Gallery</h2>
    <a href="{{ route('admin.gallery.create') }}" class="btn btn-primary">+ Upload Image</a>
</div>
<div class="row g-3">
    @forelse($images as $image)
        <div class="col-md-2 col-4">
            <div class="card shadow-sm">
                <img src="{{ asset('storage/'.$image->image) }}" class="card-img-top" style="height:120px;object-fit:cover;">
                <div class="card-body p-2">
                    <p class="small mb-1">{{ $image->title ?? '-' }}</p>
                    <form action="{{ route('admin.gallery.destroy', $image) }}" method="POST" onsubmit="return confirm('Delete this image?');">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger w-100">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <p class="text-muted text-center">No images uploaded yet.</p>
    @endforelse
</div>
<div class="mt-3">{{ $images->links() }}</div>
@endsection
