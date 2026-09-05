@extends('layouts.admin')
@section('title', 'Rooms')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Rooms</h2>
    <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary">+ Add Room</a>
</div>

<form method="GET" class="row g-2 mb-3">
    <div class="col-md-4">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search room number...">
    </div>
    <div class="col-md-3">
        <select name="status" class="form-select">
            <option value="">All statuses</option>
            @foreach(['available','partially_occupied','full','maintenance'] as $status)
                <option value="{{ $status }}" @selected(request('status')===$status)>{{ str_replace('_',' ', ucfirst($status)) }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2">
        <button class="btn btn-outline-secondary w-100">Filter</button>
    </div>
</form>

<div class="card shadow-sm">
    <table class="table table-hover mb-0">
        <thead>
            <tr>
                <th>Room #</th><th>Type</th><th>Floor</th><th>Capacity</th><th>Occupied</th><th>Price</th><th>Status</th><th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($rooms as $room)
                <tr>
                    <td>{{ $room->room_number }}</td>
                    <td>{{ $room->room_type }}</td>
                    <td>{{ $room->floor ?? '-' }}</td>
                    <td>{{ $room->capacity }}</td>
                    <td>{{ $room->current_occupancy }}</td>
                    <td>Rs. {{ number_format($room->price, 0) }}</td>
                    <td><span class="badge bg-{{ $room->status === 'available' ? 'success' : ($room->status === 'full' ? 'danger' : 'warning') }}">{{ str_replace('_',' ',ucfirst($room->status)) }}</span></td>
                    <td class="text-end">
                        <a href="{{ route('admin.rooms.edit', $room) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form action="{{ route('admin.rooms.destroy', $room) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this room?');">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center text-muted py-4">No rooms found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="mt-3">{{ $rooms->links() }}</div>
@endsection
