<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreResidentRequest;
use App\Http\Requests\Admin\UpdateResidentRequest;
use App\Models\Resident;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ResidentController extends Controller
{
    /**
     * Live duplicate-CNIC lookup used by the create/edit form (AJAX, on blur).
     * Accepts either a raw 13-digit string or the dashed "12345-1234567-1"
     * format so the check matches whatever the FormRequest will normalize to.
     */
    public function checkIdentification(Request $request): JsonResponse
    {
        $request->validate([
            'identification_number' => ['required', 'string'],
            'id_type' => ['nullable', 'in:cnic,passport,other'],
            'resident_id' => ['nullable', 'integer'],
        ]);

        if ($request->input('id_type', 'cnic') === 'cnic') {
            $digits = preg_replace('/\D/', '', $request->identification_number);
            $normalized = strlen($digits) === 13
                ? substr($digits, 0, 5).'-'.substr($digits, 5, 7).'-'.substr($digits, 12, 1)
                : trim($request->identification_number);
        } else {
            $normalized = trim($request->identification_number);
        }

        $match = Resident::where('identification_number', $normalized)
            ->when($request->filled('resident_id'), fn ($q) => $q->where('id', '!=', $request->resident_id))
            ->first(['id', 'name', 'status']);

        return response()->json([
            'exists' => (bool) $match,
            'resident' => $match ? [
                'id' => $match->id,
                'name' => $match->name,
                'status' => $match->status,
                'url' => route('admin.residents.show', $match->id),
            ] : null,
        ]);
    }

    public function index(Request $request): View
    {
        $residents = Resident::query()
            ->when($request->filled('search'), function ($q) use ($request) {
                $term = '%'.$request->search.'%';
                $q->where(function ($q2) use ($term) {
                    $q2->where('name', 'like', $term)
                        ->orWhere('phone', 'like', $term)
                        ->orWhere('identification_number', 'like', $term);
                });
            })
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.residents.index', compact('residents'));
    }

    public function create(): View
    {
        return view('admin.residents.create');
    }

    public function store(StoreResidentRequest $request): RedirectResponse
    {
        Resident::create($request->validated());

        return redirect()->route('admin.residents.index')->with('success', 'Resident added successfully.');
    }

    /**
     * Streams the (currently filtered) resident list as CSV rather than
     * loading everything into memory — safe even if the list grows large.
     */
    public function export(Request $request): StreamedResponse
    {
        $residents = Resident::query()
            ->when($request->filled('search'), function ($q) use ($request) {
                $term = '%'.$request->search.'%';
                $q->where(function ($q2) use ($term) {
                    $q2->where('name', 'like', $term)
                        ->orWhere('phone', 'like', $term)
                        ->orWhere('identification_number', 'like', $term);
                });
            })
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->orderBy('name')
            ->cursor();

        $filename = 'residents-'.now()->format('Y-m-d').'.csv';

        $callback = function () use ($residents) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Name', 'Guardian', 'ID Type', 'ID Number', 'Phone', 'Email', 'Status', 'Check-in', 'Check-out']);

            foreach ($residents as $resident) {
                fputcsv($handle, [
                    $resident->name,
                    $resident->guardian_name,
                    $resident->id_type,
                    $resident->identification_number,
                    $resident->phone,
                    $resident->email,
                    $resident->status,
                    optional($resident->check_in_date)->format('Y-m-d'),
                    optional($resident->check_out_date)->format('Y-m-d'),
                ]);
            }

            fclose($handle);
        };

        return response()->streamDownload($callback, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function show(Resident $resident): View
    {
        $resident->load('allocations.room');

        return view('admin.residents.show', compact('resident'));
    }

    public function edit(Resident $resident): View
    {
        return view('admin.residents.edit', compact('resident'));
    }

    public function update(UpdateResidentRequest $request, Resident $resident): RedirectResponse
    {
        $resident->update($request->validated());

        return redirect()->route('admin.residents.index')->with('success', 'Resident updated successfully.');
    }

    public function destroy(Resident $resident): RedirectResponse
    {
        if ($resident->allocations()->exists() || $resident->payments()->exists()) {
            return back()->with('error', 'This resident has allocation or payment history and cannot be deleted. Set their status to "Inactive" instead to preserve records.');
        }

        try {
            $resident->delete();
        } catch (QueryException $e) {
            return back()->with('error', 'This resident could not be deleted because other records still reference them.');
        }

        return redirect()->route('admin.residents.index')->with('success', 'Resident deleted successfully.');
    }
}
