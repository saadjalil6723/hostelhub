@extends('layouts.admin')
@section('title', 'My Profile')
@section('content')
<h2 class="mb-4">My Profile</h2>
<div class="card shadow-sm p-4 col-lg-6">
    <form method="POST" action="{{ route('admin.profile.update') }}">
        @csrf @method('PUT')
        <div class="mb-3">
            <label class="form-label">Name *</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $admin->name) }}" required>
            @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Email *</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $admin->email) }}" required>
            @error('email')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>

        <hr>
        <h6>Change Password</h6>
        <p class="text-muted small">Leave blank to keep your current password.</p>

        <div class="mb-3">
            <label class="form-label">Current Password</label>
            <input type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror">
            @error('current_password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">New Password</label>
                <input type="password" name="new_password" class="form-control @error('new_password') is-invalid @enderror">
                <div class="form-text">At least 8 characters, with upper/lowercase letters and a number.</div>
                @error('new_password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Confirm New Password</label>
                <input type="password" name="new_password_confirmation" class="form-control">
            </div>
        </div>

        <button class="btn btn-primary">Save Changes</button>
    </form>
</div>
@endsection
