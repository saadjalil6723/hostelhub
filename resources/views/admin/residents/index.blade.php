@extends('layouts.admin')
@section('title', 'Residents')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Residents</h2>
    <div>
        <a href="{{ route('admin.residents.export', request()->query()) }}" class="btn btn-outline-secondary">Export CSV</a>
        <a href="{{ route('admin.residents.create') }}" class="btn btn-primary">+ Add Resident</a>
    </div>
</div>

<form method="GET" class="row g-2 mb-3">
    <div class="col-md-4">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search name, phone, CNIC...">
    </div>
    <div class="col-md-3">
        <select name="status" class="form-select">
            <option value="">All statuses</option>
            @foreach(['active','checked_out','inactive'] as $status)
                <option value="{{ $status }}" @selected(request('status')===$status)>{{ ucfirst(str_replace('_',' ',$status)) }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-2"><button class="btn btn-outline-secondary w-100">Filter</button></div>
</form>

<div class="card shadow-sm">
    <table class="table table-hover mb-0">
        <thead><tr><th>Name</th><th>Phone</th><th>CNIC/ID</th><th>Check-in</th><th>Status</th><th></th></tr></thead>
        <tbody>
        @forelse($residents as $resident)
            <tr>
                <td><a href="{{ route('admin.residents.show', $resident) }}">{{ $resident->name }}</a></td>
                <td>{{ $resident->phone }}</td>
                <td>{{ $resident->identification_number ?? '-' }}</td>
                <td>{{ optional($resident->check_in_date)->format('d M Y') ?? '-' }}</td>
                <td><span class="badge bg-{{ $resident->status==='active'?'success':'secondary' }}">{{ ucfirst($resident->status) }}</span></td>
                <td class="text-end">
                    <a href="{{ route('admin.residents.edit', $resident) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                    <form action="{{ route('admin.residents.destroy', $resident) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this resident?');">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center text-muted py-4">No residents found.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="mt-3">{{ $residents->links() }}</div>
@endsection
