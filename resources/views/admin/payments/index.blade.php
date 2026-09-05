@extends('layouts.admin')
@section('title', 'Payments')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Payments &amp; Rent Collection</h2>
    <a href="{{ route('admin.payments.create') }}" class="btn btn-primary">+ Record Payment</a>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card stat-card shadow-sm border-start border-success border-4">
            <div class="card-body">
                <p class="text-muted small mb-1">Collected This Month</p>
                <h3 class="mb-0">Rs. {{ number_format($totalThisMonth, 0) }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card shadow-sm border-start border-primary border-4">
            <div class="card-body">
                <p class="text-muted small mb-1">Collected All Time</p>
                <h3 class="mb-0">Rs. {{ number_format($totalAllTime, 0) }}</h3>
            </div>
        </div>
    </div>
</div>

<form method="GET" class="row g-2 mb-3">
    <div class="col-md-4">
        <select name="resident_id" class="form-select">
            <option value="">All residents</option>
            @foreach($residents as $resident)
                <option value="{{ $resident->id }}" @selected(request('resident_id')==$resident->id)>{{ $resident->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <input type="month" name="for_month" class="form-control" value="{{ request('for_month') }}">
    </div>
    <div class="col-md-2"><button class="btn btn-outline-secondary w-100">Filter</button></div>
</form>

<div class="card shadow-sm">
    <table class="table table-hover mb-0">
        <thead><tr><th>Resident</th><th>Amount</th><th>For Month</th><th>Paid On</th><th>Method</th><th>Reference</th><th></th></tr></thead>
        <tbody>
        @forelse($payments as $payment)
            <tr>
                <td><a href="{{ route('admin.residents.show', $payment->resident_id) }}">{{ $payment->resident->name ?? 'N/A' }}</a></td>
                <td>Rs. {{ number_format($payment->amount, 0) }}</td>
                <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $payment->for_month)->format('F Y') }}</td>
                <td>{{ $payment->payment_date->format('d M Y') }}</td>
                <td><span class="badge bg-secondary">{{ str_replace('_',' ', ucfirst($payment->method)) }}</span></td>
                <td>{{ $payment->reference_number ?? '-' }}</td>
                <td class="text-end">
                    <form action="{{ route('admin.payments.destroy', $payment) }}" method="POST" onsubmit="return confirm('Delete this payment record?');">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="text-center text-muted py-4">No payments recorded yet.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
<div class="mt-3">{{ $payments->links() }}</div>
@endsection
