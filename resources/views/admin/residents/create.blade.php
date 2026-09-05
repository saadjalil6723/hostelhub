@extends('layouts.admin')
@section('title', 'Add Resident')
@section('content')
<h2 class="mb-4">Add Resident</h2>
<div class="card shadow-sm p-4">
    <form method="POST" action="{{ route('admin.residents.store') }}">
        @csrf
        @include('admin.residents._form')
        <button class="btn btn-primary">Save Resident</button>
    </form>
</div>
@endsection
