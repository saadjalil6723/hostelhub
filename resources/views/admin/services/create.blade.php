@extends('layouts.admin')
@section('title', 'Add Service')
@section('content')
<h2 class="mb-4">Add Service</h2>
<div class="card shadow-sm p-4">
    <form method="POST" action="{{ route('admin.services.store') }}" enctype="multipart/form-data">
        @csrf
        @include('admin.services._form')
        <button class="btn btn-primary">Save Service</button>
    </form>
</div>
@endsection
