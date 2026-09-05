@extends('layouts.admin')
@section('title', 'Room Allocations')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Room Allocations</h2>
    <a href="{{ route('admin.allocations.create') }}" class="btn btn-primary">+ Allocate Room</a>
</div>

<div class="card shadow-sm">
    <table class="table table-hover mb-0">
        <thead><tr><th>Resident</th><th>Room</th><th>Bed</th><th>Allocated</th><th>Checkout</th><th>Status</th><th></th></tr></thead>
        <tbody>
        @forelse($allocations as $allocation)
            <tr>
                <td>{{ $allocation->resident->name ?? 'N/A' }}</td>
                <td>{{ $allocation->room->room_number ?? 'N/A' }}</td>
                <td>{{ $allocation->bed_number ?? '-' }}</td>
                <td>{{ $allocation->allocation_date->format('d M Y') }}</td>
                <td>{{ optional($allocation->checkout_date)->format('d M Y') ?? '-' }}</td>
                <td><span class="badge bg-{{ $allocation->status==='active'?'success':'secondary' }}">{{ ucfirst($allocation->status) }}</span></td>
                <td class="text-end">
                    @if($allocation->status === 'active')
                        <form action="{{ route('admin.allocations.end', $allocation) }}" method="POST" class="d-inline" onsubmit="return confirm('End this allocation?');">
                            @csrf @method('PATCH')
                            <button class="btn btn-sm btn-outline-warning">End</button>
                        </form>
                    @endif
                    <form action="{{ route('admin.allocations.destroy', $allocation) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this record?');">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="text-center text-muted py-4">No allocations found.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="mt-3">{{ $allocations->links() }}</div>
@endsection
