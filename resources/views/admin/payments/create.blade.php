@extends('layouts.admin')
@section('title', 'Record Payment')
@section('content')
<h2 class="mb-4">Record a Payment</h2>
<div class="card shadow-sm p-4">
    <form method="POST" action="{{ route('admin.payments.store') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Resident *</label>
            <select name="resident_id" id="resident_id" class="form-select @error('resident_id') is-invalid @enderror" required>
                <option value="">-- Select resident --</option>
                @foreach($residents as $resident)
                    <option value="{{ $resident->id }}" @selected(old('resident_id')==$resident->id)>{{ $resident->name }} ({{ $resident->phone }})</option>
                @endforeach
            </select>
            <div id="allocation-hint" class="form-text"></div>
            @error('resident_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <input type="hidden" name="room_allocation_id" id="room_allocation_id" value="{{ old('room_allocation_id') }}">

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Amount (Rs.) *</label>
                <input type="number" step="0.01" min="0.01" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" value="{{ old('amount') }}" required>
                @error('amount')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">For Month *</label>
                <input type="month" name="for_month" class="form-control @error('for_month') is-invalid @enderror" value="{{ old('for_month', date('Y-m')) }}" required>
                @error('for_month')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Payment Date *</label>
                <input type="date" name="payment_date" class="form-control @error('payment_date') is-invalid @enderror" value="{{ old('payment_date', date('Y-m-d')) }}" required>
                @error('payment_date')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Method *</label>
                <select name="method" class="form-select" required>
                    @foreach(['cash'=>'Cash','bank_transfer'=>'Bank Transfer','card'=>'Card','mobile_wallet'=>'Mobile Wallet','other'=>'Other'] as $value => $label)
                        <option value="{{ $value }}" @selected(old('method')===$value)>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Reference Number</label>
            <input type="text" name="reference_number" class="form-control" value="{{ old('reference_number') }}" placeholder="Transaction ID, cheque no., etc.">
        </div>
        <div class="mb-3">
            <label class="form-label">Notes</label>
            <textarea name="notes" rows="2" class="form-control">{{ old('notes') }}</textarea>
        </div>

        <button class="btn btn-primary">Save Payment</button>
    </form>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const residentSelect = document.getElementById('resident_id');
    const allocationInput = document.getElementById('room_allocation_id');
    const hint = document.getElementById('allocation-hint');
    const amountInput = document.getElementById('amount');
    const url = '{{ route('admin.payments.resident-allocation') }}';

    residentSelect.addEventListener('change', function () {
        allocationInput.value = '';
        hint.textContent = '';

        if (!this.value) return;

        fetch(url + '?resident_id=' + encodeURIComponent(this.value), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.allocation) {
                    allocationInput.value = data.allocation.id;
                    hint.textContent = 'Currently in Room ' + data.allocation.room_number +
                        (data.allocation.monthly_price ? ' (Rs. ' + Number(data.allocation.monthly_price).toLocaleString() + '/month)' : '');
                    if (data.allocation.monthly_price && !amountInput.value) {
                        amountInput.value = data.allocation.monthly_price;
                    }
                } else {
                    hint.textContent = 'This resident has no active room allocation.';
                }
            })
            .catch(() => { hint.textContent = ''; });
    });
})();
</script>
@endpush
