@extends('layouts.admin')
@section('title', 'Resident Details')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">{{ $resident->name }}</h2>
    <a href="{{ route('admin.residents.edit', $resident) }}" class="btn btn-outline-primary">Edit</a>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card shadow-sm p-3">
            <table class="table table-borderless mb-0">
                <tr><th>Guardian</th><td>{{ $resident->guardian_name ?? '-' }}</td></tr>
                <tr><th>CNIC/ID</th><td>{{ $resident->identification_number ?? '-' }} @if($resident->identification_number)<span class="badge bg-light text-dark border">{{ ucfirst($resident->id_type) }}</span>@endif</td></tr>
                <tr><th>Phone</th><td>{{ $resident->phone }}</td></tr>
                <tr><th>Email</th><td>{{ $resident->email ?? '-' }}</td></tr>
                <tr><th>Emergency Contact</th><td>{{ $resident->emergency_contact ?? '-' }}</td></tr>
                <tr><th>Address</th><td>{{ $resident->address ?? '-' }}</td></tr>
                <tr><th>Status</th><td><span class="badge bg-secondary">{{ ucfirst($resident->status) }}</span></td></tr>
                <tr><th>Notes</th><td>{{ $resident->notes ?? '-' }}</td></tr>
            </table>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm p-3">
            <h6>Room Allocation History</h6>
            <ul class="list-group list-group-flush">
                @forelse($resident->allocations as $allocation)
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Room {{ $allocation->room->room_number ?? 'N/A' }} ({{ $allocation->allocation_date->format('d M Y') }})</span>
                        <span class="badge bg-{{ $allocation->status==='active'?'success':'secondary' }}">{{ ucfirst($allocation->status) }}</span>
                    </li>
                @empty
                    <li class="list-group-item text-muted">No allocation history.</li>
                @endforelse
            </ul>
        </div>

        <div class="card shadow-sm p-3 mt-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="mb-0">Payment History</h6>
                <a href="{{ route('admin.payments.create') }}" class="btn btn-sm btn-outline-primary">+ Record Payment</a>
            </div>
            <ul class="list-group list-group-flush">
                @forelse($resident->payments()->latest('payment_date')->get() as $payment)
                    <li class="list-group-item d-flex justify-content-between">
                        <span>{{ \Carbon\Carbon::createFromFormat('Y-m', $payment->for_month)->format('F Y') }} &mdash; Rs. {{ number_format($payment->amount, 0) }}</span>
                        <span class="text-muted small">{{ $payment->payment_date->format('d M Y') }}</span>
                    </li>
                @empty
                    <li class="list-group-item text-muted">No payments recorded yet.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
