<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRoomRequest;
use App\Http\Requests\Admin\UpdateRoomRequest;
use App\Models\Room;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoomController extends Controller
{
    public function index(Request $request): View
    {
        $rooms = Room::query()
            ->when($request->filled('search'), fn ($q) => $q->where('room_number', 'like', '%'.$request->search.'%'))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.rooms.index', compact('rooms'));
    }

    public function create(): View
    {
        return view('admin.rooms.create');
    }

    public function store(StoreRoomRequest $request): RedirectResponse
    {
        Room::create($request->validated());

        return redirect()->route('admin.rooms.index')->with('success', 'Room created successfully.');
    }

    public function edit(Room $room): View
    {
        return view('admin.rooms.edit', compact('room'));
    }

    public function update(UpdateRoomRequest $request, Room $room): RedirectResponse
    {
        $room->update($request->validated());

        return redirect()->route('admin.rooms.index')->with('success', 'Room updated successfully.');
    }

    public function destroy(Room $room): RedirectResponse
    {
        // Guard against wiping out audit history: a room with ANY allocation
        // record (active or historical/ended) can't be deleted, not just one
        // with current residents. The DB foreign key also restricts this now
        // (restrictOnDelete) — this check just gives a friendly message
        // instead of a raw SQL error before we even try.
        if ($room->allocations()->exists()) {
            return back()->with('error', 'This room has allocation history (current or past residents) and cannot be deleted. Archive it by setting status to "Maintenance" instead.');
        }

        try {
            $room->delete();
        } catch (QueryException $e) {
            return back()->with('error', 'This room could not be deleted because other records still reference it.');
        }

        return redirect()->route('admin.rooms.index')->with('success', 'Room deleted successfully.');
    }
}
