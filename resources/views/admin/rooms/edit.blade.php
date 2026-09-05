@extends('layouts.admin')
@section('title', 'Edit Room')

@section('content')
<h2 class="mb-4">Edit Room {{ $room->room_number }}</h2>
<div class="card shadow-sm p-4">
    <form method="POST" action="{{ route('admin.rooms.update', $room) }}">
        @csrf @method('PUT')
        @include('admin.rooms._form', ['room' => $room])
        <button class="btn btn-primary">Update Room</button>
    </form>
</div>
@endsection
