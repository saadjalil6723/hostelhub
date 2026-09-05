<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Room Number *</label>
        <input type="text" name="room_number" class="form-control" value="{{ old('room_number', $room->room_number ?? '') }}" required>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Floor</label>
        <input type="text" name="floor" class="form-control" value="{{ old('floor', $room->floor ?? '') }}">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Room Type *</label>
        <input type="text" name="room_type" class="form-control" value="{{ old('room_type', $room->room_type ?? '') }}" required placeholder="e.g. Double Sharing">
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Capacity *</label>
        <input type="number" name="capacity" min="1" class="form-control" value="{{ old('capacity', $room->capacity ?? 1) }}" required>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Price (per month) *</label>
        <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', $room->price ?? '') }}" required>
    </div>
    <div class="col-md-4 mb-3">
        <label class="form-label">Status *</label>
        <select name="status" class="form-select" required>
            @foreach(['available','partially_occupied','full','maintenance'] as $status)
                <option value="{{ $status }}" @selected(old('status', $room->status ?? 'available')===$status)>{{ str_replace('_',' ',ucfirst($status)) }}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="mb-3">
    <label class="form-label">Facilities</label>
    <input type="text" name="facilities" class="form-control" value="{{ old('facilities', $room->facilities ?? '') }}" placeholder="Comma separated, e.g. AC, Wi-Fi, Attached Bath">
</div>
<div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" rows="3" class="form-control">{{ old('description', $room->description ?? '') }}</textarea>
</div>
