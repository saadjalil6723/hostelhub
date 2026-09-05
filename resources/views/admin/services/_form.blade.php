<div class="mb-3">
    <label class="form-label">Title *</label>
    <input type="text" name="title" class="form-control" value="{{ old('title', $service->title ?? '') }}" required>
</div>
<div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" rows="3" class="form-control">{{ old('description', $service->description ?? '') }}</textarea>
</div>
<div class="mb-3">
    <label class="form-label">Image</label>
    <input type="file" name="image" class="form-control" accept="image/*">
    @if(!empty($service?->image))
        <img src="{{ asset('storage/'.$service->image) }}" class="mt-2 rounded" style="height:80px;">
    @endif
</div>
<div class="mb-3 form-check">
    <input type="hidden" name="status" value="0">
    <input type="checkbox" name="status" value="1" class="form-check-input" id="status" @checked(old('status', $service->status ?? true))>
    <label class="form-check-label" for="status">Enabled (visible on public site)</label>
</div>
