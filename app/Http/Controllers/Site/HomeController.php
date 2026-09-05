<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\GalleryImage;
use App\Models\Room;
use App\Models\Service;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $services = Service::active()->take(6)->get();
        $rooms = Room::where('status', '!=', 'maintenance')->orderBy('room_number')->take(10)->get();
        $galleryPreview = GalleryImage::latest()->take(6)->get();

        return view('site.home', compact('services', 'rooms', 'galleryPreview'));
    }

    public function about(): View
    {
        return view('site.about');
    }
}
