@extends('layouts.admin')
@section('title', 'Edit Resident')
@section('content')
<h2 class="mb-4">Edit Resident</h2>
<div class="card shadow-sm p-4">
    <form method="POST" action="{{ route('admin.residents.update', $resident) }}">
        @csrf @method('PUT')
        @include('admin.residents._form', ['resident' => $resident])
        <button class="btn btn-primary">Update Resident</button>
    </form>
</div>
@endsection
