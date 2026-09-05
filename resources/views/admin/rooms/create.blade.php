@extends('layouts.admin')
@section('title', 'Add Room')

@section('content')
<h2 class="mb-4">Add Room</h2>
<div class="card shadow-sm p-4">
    <form method="POST" action="{{ route('admin.rooms.store') }}">
        @csrf
        @include('admin.rooms._form')
        <button class="btn btn-primary">Save Room</button>
    </form>
</div>
@endsection
