<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\GalleryImage;
use App\Models\Payment;
use App\Models\Resident;
use App\Models\Room;
use App\Models\Service;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_rooms' => Room::count(),
            'available_rooms' => Room::where('status', 'available')->count(),
            'occupied_rooms' => Room::whereIn('status', ['partially_occupied', 'full'])->count(),
            'total_residents' => Resident::count(),
            'active_residents' => Resident::where('status', 'active')->count(),
            'total_services' => Service::count(),
            'gallery_count' => GalleryImage::count(),
            'unread_messages' => ContactMessage::unread()->count(),
            'collected_this_month' => Payment::where('for_month', now()->format('Y-m'))->sum('amount'),
        ];

        $recentMessages = ContactMessage::latest()->take(5)->get();
        $recentResidents = Resident::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentMessages', 'recentResidents'));
    }
}
