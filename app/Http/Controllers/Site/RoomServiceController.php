<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Models\Service;
use Illuminate\View\View;

class RoomServiceController extends Controller
{
    public function index(): View
    {
        $rooms = Room::where('status', '!=', 'maintenance')->orderBy('room_number')->get();
        $services = Service::active()->orderBy('title')->get();

        return view('site.rooms', compact('rooms', 'services'));
    }
}
