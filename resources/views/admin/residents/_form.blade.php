<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Full Name *</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $resident->name ?? '') }}" required>
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Guardian Name</label>
        <input type="text" name="guardian_name" class="form-control" value="{{ old('guardian_name', $resident->guardian_name ?? '') }}">
    </div>

    <div class="col-md-3 mb-3">
        <label class="form-label">ID Type *</label>
        <select name="id_type" id="id_type" class="form-select" required>
            @foreach(['cnic' => 'CNIC', 'passport' => 'Passport', 'other' => 'Other'] as $value => $label)
                <option value="{{ $value }}" @selected(old('id_type', $resident->id_type ?? 'cnic')===$value)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3 mb-3">
        <label class="form-label">ID Number</label>
        <input
            type="text"
            name="identification_number"
            id="identification_number"
            class="form-control @error('identification_number') is-invalid @enderror"
            value="{{ old('identification_number', $resident->identification_number ?? '') }}"
            placeholder="12345-1234567-1"
            maxlength="30"
            autocomplete="off"
        >
        <div id="cnic-feedback" class="form-text"></div>
        @error('identification_number')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Phone *</label>
        <input
            type="text"
            name="phone"
            class="form-control @error('phone') is-invalid @enderror"
            value="{{ old('phone', $resident->phone ?? '') }}"
            placeholder="03001234567"
            required
        >
        <div class="form-text">Format: 03XXXXXXXXX or +923XXXXXXXXX</div>
        @error('phone')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $resident->email ?? '') }}">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Emergency Contact</label>
        <input type="text" name="emergency_contact" class="form-control" value="{{ old('emergency_contact', $resident->emergency_contact ?? '') }}">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Check-in Date</label>
        <input type="date" name="check_in_date" class="form-control" value="{{ old('check_in_date', optional($resident->check_in_date ?? null)->format('Y-m-d')) }}">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Check-out Date</label>
        <input type="date" name="check_out_date" class="form-control" value="{{ old('check_out_date', optional($resident->check_out_date ?? null)->format('Y-m-d')) }}">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Status *</label>
        <select name="status" class="form-select" required>
            @foreach(['active','checked_out','inactive'] as $status)
                <option value="{{ $status }}" @selected(old('status', $resident->status ?? 'active')===$status)>{{ ucfirst(str_replace('_',' ',$status)) }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="mb-3">
    <label class="form-label">Address</label>
    <textarea name="address" rows="2" class="form-control">{{ old('address', $resident->address ?? '') }}</textarea>
</div>
<div class="mb-3">
    <label class="form-label">Notes</label>
    <textarea name="notes" rows="2" class="form-control">{{ old('notes', $resident->notes ?? '') }}</textarea>
</div>

@push('scripts')
<script>
(function () {
    const input = document.getElementById('identification_number');
    const idType = document.getElementById('id_type');
    const feedback = document.getElementById('cnic-feedback');
    const submitBtn = document.querySelector('button[type="submit"]');
    const residentId = {{ isset($resident) ? $resident->id : 'null' }};
    const checkUrl = '{{ route('admin.residents.check-identification') }}';
    let debounceTimer;

    function resetFeedback() {
        feedback.textContent = '';
        feedback.className = 'form-text';
        input.classList.remove('is-invalid');
        if (submitBtn) submitBtn.disabled = false;
    }

    // Auto-format only applies to CNIC; passport/other numbers are free text.
    input.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        resetFeedback();

        if (idType.value === 'cnic') {
            const digits = this.value.replace(/\D/g, '').slice(0, 13);
            let formatted = digits;
            if (digits.length > 5) formatted = digits.slice(0, 5) + '-' + digits.slice(5);
            if (digits.length > 12) formatted = formatted.slice(0, 13) + '-' + digits.slice(12);
            this.value = formatted;

            if (digits.length === 13) {
                debounceTimer = setTimeout(checkDuplicate, 400);
            }
        } else if (this.value.trim().length >= 3) {
            debounceTimer = setTimeout(checkDuplicate, 400);
        }
    });

    idType.addEventListener('change', function () {
        input.value = '';
        resetFeedback();
    });

    function checkDuplicate() {
        feedback.textContent = 'Checking...';
        feedback.className = 'form-text text-muted';

        const params = new URLSearchParams({
            identification_number: input.value,
            id_type: idType.value,
        });
        if (residentId) params.set('resident_id', residentId);

        fetch(checkUrl + '?' + params.toString(), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.exists) {
                    feedback.innerHTML = 'This CNIC is already registered to <a href="' +
                        data.resident.url + '" target="_blank">' + data.resident.name + '</a> (' + data.resident.status + ').';
                    feedback.className = 'form-text text-danger';
                    input.classList.add('is-invalid');
                    if (submitBtn) submitBtn.disabled = true;
                } else {
                    feedback.textContent = 'CNIC is available.';
                    feedback.className = 'form-text text-success';
                    input.classList.remove('is-invalid');
                    if (submitBtn) submitBtn.disabled = false;
                }
            })
            .catch(() => {
                feedback.textContent = '';
                if (submitBtn) submitBtn.disabled = false;
            });
    }

    // Simple phone formatting guard (digits and leading + only)
    const phoneInput = document.querySelector('input[name="phone"]');
    phoneInput.addEventListener('input', function () {
        this.value = this.value.replace(/[^\d+]/g, '');
    });
})();
</script>
@endpush
