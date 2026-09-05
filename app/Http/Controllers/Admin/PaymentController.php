<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePaymentRequest;
use App\Models\Payment;
use App\Models\Resident;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(Request $request): View
    {
        $payments = Payment::with('resident')
            ->when($request->filled('resident_id'), fn ($q) => $q->where('resident_id', $request->resident_id))
            ->when($request->filled('for_month'), fn ($q) => $q->where('for_month', $request->for_month))
            ->latest('payment_date')
            ->paginate(20)
            ->withQueryString();

        $totalThisMonth = Payment::where('for_month', now()->format('Y-m'))->sum('amount');
        $totalAllTime = Payment::sum('amount');
        $residents = Resident::orderBy('name')->get(['id', 'name']);

        return view('admin.payments.index', compact('payments', 'totalThisMonth', 'totalAllTime', 'residents'));
    }

    public function create(): View
    {
        $residents = Resident::where('status', 'active')->orderBy('name')->get();

        return view('admin.payments.create', compact('residents'));
    }

    /**
     * Returns a resident's active allocation (if any) so the form can
     * auto-fill and scope "for_month" sensibly once a resident is picked.
     */
    public function residentAllocation(Request $request): JsonResponse
    {
        $request->validate(['resident_id' => ['required', 'exists:residents,id']]);

        $resident = Resident::with(['allocations' => fn ($q) => $q->where('status', 'active')->with('room')])
            ->findOrFail($request->resident_id);

        $active = $resident->allocations->first();

        return response()->json([
            'allocation' => $active ? [
                'id' => $active->id,
                'room_number' => $active->room->room_number ?? null,
                'monthly_price' => $active->room->price ?? null,
            ] : null,
        ]);
    }

    public function store(StorePaymentRequest $request): RedirectResponse
    {
        $data = $request->validated();

        DB::transaction(function () use ($data) {
            // If the payment isn't explicitly tied to an allocation, attach
            // the resident's current active one automatically (if any) so
            // the payment history stays linked to which room it paid for.
            if (empty($data['room_allocation_id'])) {
                $resident = Resident::find($data['resident_id']);
                $data['room_allocation_id'] = $resident?->currentAllocation()?->id;
            }

            Payment::create($data);
        });

        return redirect()->route('admin.payments.index')->with('success', 'Payment recorded successfully.');
    }

    public function destroy(Payment $payment): RedirectResponse
    {
        $payment->delete();

        return redirect()->route('admin.payments.index')->with('success', 'Payment record deleted.');
    }
}
