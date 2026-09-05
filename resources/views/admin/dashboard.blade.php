@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<h2 class="mb-4">Dashboard</h2>

<div class="row g-3 mb-4">
    @php
        $cards = [
            ['label' => 'Total Rooms', 'value' => $stats['total_rooms'], 'color' => 'primary'],
            ['label' => 'Available Rooms', 'value' => $stats['available_rooms'], 'color' => 'success'],
            ['label' => 'Occupied Rooms', 'value' => $stats['occupied_rooms'], 'color' => 'warning'],
            ['label' => 'Total Residents', 'value' => $stats['total_residents'], 'color' => 'info'],
            ['label' => 'Active Residents', 'value' => $stats['active_residents'], 'color' => 'info'],
            ['label' => 'Total Services', 'value' => $stats['total_services'], 'color' => 'secondary'],
            ['label' => 'Gallery Images', 'value' => $stats['gallery_count'], 'color' => 'secondary'],
            ['label' => 'Unread Messages', 'value' => $stats['unread_messages'], 'color' => 'danger'],
            ['label' => 'Collected This Month', 'value' => 'Rs. '.number_format($stats['collected_this_month'], 0), 'color' => 'success'],
        ];
    @endphp
    @foreach($cards as $card)
        <div class="col-md-3 col-6">
            <div class="card stat-card shadow-sm border-start border-{{ $card['color'] }} border-4">
                <div class="card-body">
                    <p class="text-muted small mb-1">{{ $card['label'] }}</p>
                    <h3 class="mb-0">{{ $card['value'] }}</h3>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white fw-bold">Recent Contact Messages</div>
            <ul class="list-group list-group-flush">
                @forelse($recentMessages as $message)
                    <li class="list-group-item d-flex justify-content-between">
                        <a href="{{ route('admin.contacts.show', $message) }}">{{ $message->name }}</a>
                        <span class="badge bg-{{ $message->status === 'unread' ? 'danger' : 'secondary' }}">{{ $message->status }}</span>
                    </li>
                @empty
                    <li class="list-group-item text-muted">No messages yet.</li>
                @endforelse
            </ul>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white fw-bold">Recently Added Residents</div>
            <ul class="list-group list-group-flush">
                @forelse($recentResidents as $resident)
                    <li class="list-group-item d-flex justify-content-between">
                        <a href="{{ route('admin.residents.show', $resident) }}">{{ $resident->name }}</a>
                        <span class="badge bg-secondary">{{ $resident->status }}</span>
                    </li>
                @empty
                    <li class="list-group-item text-muted">No residents yet.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
@endsection
