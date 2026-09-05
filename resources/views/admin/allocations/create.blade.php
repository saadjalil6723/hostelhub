@extends('layouts.admin')
@section('title', 'Allocate Room')
@section('content')
<h2 class="mb-4">Allocate Resident to Room</h2>
<div class="card shadow-sm p-4">
    <form method="POST" action="{{ route('admin.allocations.store') }}" id="allocation-form">
        @csrf
        <div class="mb-3">
            <label class="form-label">Resident *</label>
            <select name="resident_id" id="resident_id" class="form-select @error('resident_id') is-invalid @enderror" required>
                <option value="">-- Select active resident --</option>
                @foreach($residents as $resident)
                    <option value="{{ $resident->id }}" @selected(old('resident_id')==$resident->id)>{{ $resident->name }} ({{ $resident->phone }})</option>
                @endforeach
            </select>
            <div id="resident-warning" class="alert alert-warning mt-2 d-none"></div>
            @error('resident_id')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3 d-none" id="reallocate-confirm-wrapper">
            <div class="form-check">
                <input type="checkbox" name="confirm_reallocate" value="1" class="form-check-input" id="confirm_reallocate">
                <label class="form-check-label" for="confirm_reallocate">
                    Reallocate anyway &mdash; this will automatically end the resident's current allocation.
                </label>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Room *</label>
            <select name="room_id" id="room_id" class="form-select @error('room_id') is-invalid @enderror" required>
                <option value="">-- Select available room --</option>
                @foreach($rooms as $room)
                    <option
                        value="{{ $room->id }}"
                        data-occupancy="{{ $room->current_occupancy }}"
                        data-capacity="{{ $room->capacity }}"
                        @selected(old('room_id')==$room->id)
                    >{{ $room->room_number }} ({{ $room->current_occupancy }}/{{ $room->capacity }})</option>
                @endforeach
            </select>
            <div id="room-status" class="form-text"></div>
            <div id="room-warning" class="alert alert-danger mt-2 d-none"></div>
            @error('room_id')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Bed / Seat Number</label>
            <input type="text" name="bed_number" class="form-control" value="{{ old('bed_number') }}">
        </div>
        <div class="mb-3">
            <label class="form-label">Allocation Date *</label>
            <input type="date" name="allocation_date" class="form-control" value="{{ old('allocation_date', date('Y-m-d')) }}" required>
        </div>
        <button type="submit" class="btn btn-primary" id="allocate-submit">Allocate</button>
    </form>
</div>
@endsection

@push('scripts')
<script>
(function () {
    const residentSelect = document.getElementById('resident_id');
    const residentWarning = document.getElementById('resident-warning');
    const confirmWrapper = document.getElementById('reallocate-confirm-wrapper');
    const confirmCheckbox = document.getElementById('confirm_reallocate');

    const roomSelect = document.getElementById('room_id');
    const roomStatus = document.getElementById('room-status');
    const roomWarning = document.getElementById('room-warning');

    const submitBtn = document.getElementById('allocate-submit');
    const checkActiveUrl = '{{ route('admin.allocations.check-active') }}';
    const checkCapacityUrl = '{{ route('admin.allocations.check-room-capacity') }}';

    // Two independent blockers: a resident already housed elsewhere, and a
    // room that's full. Submit stays disabled while either is true.
    let residentBlocked = false;
    let roomBlocked = false;

    function updateSubmitState() {
        submitBtn.disabled = residentBlocked || roomBlocked;
    }

    // --- Resident: active-allocation check -------------------------------
    residentSelect.addEventListener('change', function () {
        residentWarning.classList.add('d-none');
        residentWarning.innerHTML = '';
        confirmWrapper.classList.add('d-none');
        confirmCheckbox.checked = false;
        residentBlocked = false;
        updateSubmitState();

        if (!this.value) return;

        fetch(checkActiveUrl + '?resident_id=' + encodeURIComponent(this.value), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.exists) {
                    residentWarning.innerHTML = 'This resident already has an active allocation in <strong>Room ' +
                        data.allocation.room_number + '</strong> since ' + data.allocation.allocation_date +
                        '. <a href="' + data.allocation.url + '" target="_blank">View resident</a>.';
                    residentWarning.classList.remove('d-none');
                    confirmWrapper.classList.remove('d-none');
                    residentBlocked = true;
                    updateSubmitState();
                }
            })
            .catch(() => { residentBlocked = false; updateSubmitState(); });
    });

    confirmCheckbox.addEventListener('change', function () {
        residentBlocked = !this.checked;
        updateSubmitState();
    });

    // --- Room: live capacity re-check -------------------------------------
    // The dropdown is pre-filtered server-side, but occupancy can change
    // between page load and submit (another admin allocating concurrently),
    // so re-verify with a fresh query the moment a room is picked.
    roomSelect.addEventListener('change', function () {
        roomStatus.textContent = '';
        roomWarning.classList.add('d-none');
        roomWarning.innerHTML = '';
        roomBlocked = false;
        updateSubmitState();

        if (!this.value) return;

        const option = this.options[this.selectedIndex];
        roomStatus.textContent = 'Checking current occupancy...';

        fetch(checkCapacityUrl + '?room_id=' + encodeURIComponent(this.value), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
        })
            .then((res) => res.json())
            .then((data) => {
                const occ = data.room.current_occupancy;
                const cap = data.room.capacity;

                if (data.full || data.maintenance) {
                    roomStatus.textContent = '';
                    roomWarning.innerHTML = data.maintenance
                        ? 'Room ' + data.room.room_number + ' is currently marked under maintenance and cannot accept residents.'
                        : 'Room ' + data.room.room_number + ' just reached full capacity (' + occ + '/' + cap + '). Please choose another room.';
                    roomWarning.classList.remove('d-none');
                    roomBlocked = true;
                } else {
                    roomStatus.textContent = 'Room ' + data.room.room_number + ': ' + occ + '/' + cap + ' occupied \u2014 space available.';
                    roomBlocked = false;
                }
                updateSubmitState();
            })
            .catch(() => { roomBlocked = false; updateSubmitState(); });
    });
})();
</script>
@endpush
