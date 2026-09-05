@extends('layouts.admin')
@section('title', 'Edit Service')
@section('content')
<h2 class="mb-4">Edit Service</h2>
<div class="card shadow-sm p-4">
    <form method="POST" action="{{ route('admin.services.update', $service) }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        @include('admin.services._form', ['service' => $service])
        <button class="btn btn-primary">Update Service</button>
    </form>
</div>
@endsection
