<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRoomAllocationRequest;
use App\Models\Resident;
use App\Models\Room;
use App\Models\RoomAllocation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class RoomAllocationController extends Controller
{
    /**
     * Live lookup used by the allocation form (AJAX, on resident select)
     * to warn the admin before they submit a resident who already has an
     * active allocation elsewhere.
     */
    public function checkActiveAllocation(Request $request): JsonResponse
    {
        $request->validate([
            'resident_id' => ['required', 'integer', 'exists:residents,id'],
        ]);

        $existing = RoomAllocation::with('room')
            ->where('resident_id', $request->resident_id)
            ->where('status', 'active')
            ->first();

        return response()->json([
            'exists' => (bool) $existing,
            'allocation' => $existing ? [
                'id' => $existing->id,
                'room_number' => $existing->room->room_number ?? null,
                'allocation_date' => $existing->allocation_date->format('d M Y'),
                'url' => route('admin.residents.show', $existing->resident_id),
            ] : null,
        ]);
    }

    public function index(): View
    {
        $allocations = RoomAllocation::with(['resident', 'room'])
            ->latest('allocation_date')
            ->paginate(15);

        return view('admin.allocations.index', compact('allocations'));
    }

    public function create(): View
    {
        $residents = Resident::where('status', 'active')->orderBy('name')->get();
        $rooms = Room::whereIn('status', ['available', 'partially_occupied'])->orderBy('room_number')->get();

        return view('admin.allocations.create', compact('residents', 'rooms'));
    }

    /**
     * Live lookup used by the allocation form (AJAX, on room select) to
     * warn the admin if the room has since filled up (e.g. another admin
     * allocated the last bed after this page was loaded).
     */
    public function checkRoomCapacity(Request $request): JsonResponse
    {
        $request->validate([
            'room_id' => ['required', 'integer', 'exists:rooms,id'],
        ]);

        $room = Room::findOrFail($request->room_id);

        return response()->json([
            'full' => $room->isFull(),
            'maintenance' => $room->status === 'maintenance',
            'room' => [
                'room_number' => $room->room_number,
                'current_occupancy' => $room->current_occupancy,
                'capacity' => $room->capacity,
                'status' => $room->status,
            ],
        ]);
    }

    public function store(StoreRoomAllocationRequest $request): RedirectResponse
    {
        $data = $request->validated();

        return DB::transaction(function () use ($data) {
            // Lock the room row for the duration of this transaction so two
            // concurrent submissions can't both pass the isFull() check and
            // over-allocate the same room (the race the AJAX check can't close).
            $room = Room::lockForUpdate()->findOrFail($data['room_id']);

            if ($room->isFull()) {
                return back()->withInput()->with('error', 'Selected room is already at full capacity.');
            }

            // Reaching here with an existing active allocation only happens
            // when the admin explicitly checked "Reallocate anyway" — the
            // FormRequest blocks it otherwise. Close out the old allocation
            // and resync its room before creating the new one.
            if (! empty($data['confirm_reallocate'])) {
                RoomAllocation::where('resident_id', $data['resident_id'])
                    ->where('status', 'active')
                    ->get()
                    ->each(function (RoomAllocation $old) {
                        $old->update(['status' => 'ended', 'checkout_date' => now()]);
                        $old->room->syncOccupancy();
                    });
            }

            RoomAllocation::create([
                'resident_id' => $data['resident_id'],
                'room_id' => $data['room_id'],
                'bed_number' => $data['bed_number'] ?? null,
                'allocation_date' => $data['allocation_date'],
                'status' => 'active',
            ]);

            $room->syncOccupancy();

            return redirect()->route('admin.allocations.index')->with('success', 'Resident allocated to room successfully.');
        });
    }

    public function end(RoomAllocation $allocation): RedirectResponse
    {
        if ($allocation->status !== 'active') {
            return back()->with('error', 'This allocation has already ended.');
        }

        DB::transaction(function () use ($allocation) {
            $allocation->update([
                'status' => 'ended',
                'checkout_date' => now(),
            ]);

            $allocation->room->syncOccupancy();
        });

        return redirect()->route('admin.allocations.index')->with('success', 'Allocation ended and room occupancy updated.');
    }

    public function destroy(RoomAllocation $allocation): RedirectResponse
    {
        // Deleting an active allocation would silently free the resident's
        // bed without ever updating room occupancy correctly for the room's
        // history. Force ending it first so syncOccupancy() runs and the
        // checkout is recorded.
        if ($allocation->status === 'active') {
            return back()->with('error', 'End this allocation before deleting it, so the room\'s occupancy stays accurate.');
        }

        DB::transaction(function () use ($allocation) {
            $room = $allocation->room;
            $allocation->delete();
            $room->syncOccupancy();
        });

        return redirect()->route('admin.allocations.index')->with('success', 'Allocation record removed.');
    }
}
