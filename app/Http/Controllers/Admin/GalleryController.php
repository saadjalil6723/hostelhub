<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreGalleryImageRequest;
use App\Models\GalleryImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(): View
    {
        $images = GalleryImage::latest()->paginate(20);

        return view('admin.gallery.index', compact('images'));
    }

    public function create(): View
    {
        return view('admin.gallery.create');
    }

    public function store(StoreGalleryImageRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['image'] = $request->file('image')->store('gallery', 'public');

        GalleryImage::create($data);

        return redirect()->route('admin.gallery.index')->with('success', 'Image uploaded successfully.');
    }

    public function destroy(GalleryImage $galleryImage): RedirectResponse
    {
        Storage::disk('public')->delete($galleryImage->image);
        $galleryImage->delete();

        return redirect()->route('admin.gallery.index')->with('success', 'Image deleted successfully.');
    }
}
