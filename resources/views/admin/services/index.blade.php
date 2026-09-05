@extends('layouts.admin')
@section('title', 'Services')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Services &amp; Facilities</h2>
    <a href="{{ route('admin.services.create') }}" class="btn btn-primary">+ Add Service</a>
</div>
<div class="row g-3">
    @forelse($services as $service)
        <div class="col-md-4">
            <div class="card shadow-sm h-100">
                @if($service->image)
                    <img src="{{ asset('storage/'.$service->image) }}" class="card-img-top" style="height:150px;object-fit:cover;">
                @endif
                <div class="card-body">
                    <h5 class="card-title">{{ $service->title }}</h5>
                    <p class="text-muted small">{{ \Illuminate\Support\Str::limit($service->description, 80) }}</p>
                    <span class="badge bg-{{ $service->status ? 'success' : 'secondary' }}">{{ $service->status ? 'Enabled' : 'Disabled' }}</span>
                </div>
                <div class="card-footer bg-white d-flex justify-content-between">
                    <a href="{{ route('admin.services.edit', $service) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form action="{{ route('admin.services.destroy', $service) }}" method="POST" onsubmit="return confirm('Delete this service?');">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <p class="text-muted text-center">No services yet.</p>
    @endforelse
</div>
<div class="mt-3">{{ $services->links() }}</div>
@endsection
