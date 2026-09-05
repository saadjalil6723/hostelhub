@extends('layouts.admin')
@section('title', 'Upload Image')
@section('content')
<h2 class="mb-4">Upload Gallery Image</h2>
<div class="card shadow-sm p-4">
    <form method="POST" action="{{ route('admin.gallery.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label">Title</label>
            <input type="text" name="title" class="form-control" value="{{ old('title') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" rows="3" class="form-control">{{ old('description') }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Image *</label>
            <input type="file" name="image" class="form-control" accept="image/*" required>
        </div>
        <button class="btn btn-primary">Upload</button>
    </form>
</div>
@endsection
