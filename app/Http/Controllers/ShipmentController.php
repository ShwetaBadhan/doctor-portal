<?php

namespace App\Http\Controllers;

use App\Models\Shipment;
use App\Models\Invoice;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShipmentController extends Controller
{
    public function dashboard()
    {
        $counters = [
            'pending' => Shipment::pending()->count(),
            'packed' => Shipment::packed()->count(),
            'dispatched' => Shipment::dispatched()->count(),
            'delivered' => Shipment::delivered()->count(),
            'total' => Shipment::count(),
        ];

        $recent = Shipment::with(['patient', 'invoice'])
            ->latest()
            ->take(10)
            ->get();

        return view('pages.shipments.dashboard', compact('counters', 'recent'));
    }

    public function index(Request $request)
    {
        $query = Shipment::with(['patient', 'invoice']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('tracking_number', 'LIKE', "%{$search}%")
                    ->orWhere('recipient_name', 'LIKE', "%{$search}%")
                    ->orWhere('recipient_phone', 'LIKE', "%{$search}%");
            });
        }
        $statusCounts = [
            'pending' => Shipment::pending()->count(),
            'packed' => Shipment::packed()->count(),
            'dispatched' => Shipment::dispatched()->count(),
            'delivered' => Shipment::delivered()->count(),
        ];
        $shipments = $query->get();

        return view('pages.shipments.index', compact('shipments', 'statusCounts'));
    }

    public function create()
    {
        $patients = Patient::select('id', 'first_name', 'last_name', 'phone', 'address_1', 'address_2', 'city', 'state', 'pincode')
            ->orderBy('first_name')
            ->get();

        // ✅ FIXED: Just get recent invoices, no filtering
        $invoices = Invoice::select('id', 'invoice_number', 'patient_name', 'total_amount', 'invoice_date')
            ->orderBy('invoice_date', 'desc')
            ->take(50)
            ->get();

        return view('pages.shipments.create', compact('patients', 'invoices'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => 'nullable|exists:invoices,id',
            'patient_id' => 'nullable|exists:patients,id',
            'recipient_name' => 'required|string|max:255',
            'recipient_phone' => 'required|string|max:20',
            'recipient_address' => 'required|string|max:500',
            'recipient_city' => 'required|string|max:100',
            'recipient_state' => 'required|string|max:100',
            'recipient_pincode' => 'required|string|max:10',
            'courier_name' => 'nullable|string|max:100',
            'tracking_number' => 'nullable|string|max:100|unique:shipments',
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:1',
            'remarks' => 'nullable|string|max:1000',
            'recipient_country' => 'required|string|max:100', // Add this
        ]);

        $shipment = Shipment::create([
            'invoice_id' => $validated['invoice_id'] ?? null,
            'patient_id' => $validated['patient_id'] ?? null,
            'tracking_number' => $validated['tracking_number'] ?? 'TRK-' . strtoupper(uniqid()),
            'courier_name' => $validated['courier_name'] ?? null,
            'recipient_name' => $validated['recipient_name'],
            'recipient_phone' => $validated['recipient_phone'],
            'recipient_address' => $validated['recipient_address'],
            'recipient_city' => $validated['recipient_city'],
            'recipient_state' => $validated['recipient_state'],
            'recipient_pincode' => $validated['recipient_pincode'],
            'items' => $validated['items'],
            'status' => 'pending',
            'created_by' => Auth::id() ?? 'system',
            'remarks' => $validated['remarks'] ?? null, // Add this
            'recipient_country' => $validated['recipient_country'],
        ]);

        return redirect()->route('shipments.show', $shipment)
            ->with('success', 'Shipment created successfully!');
    }

    public function show(Shipment $shipment)
    {
        $shipment->load(['patient', 'invoice']);
        return view('pages.shipments.show', compact('shipment'));
    }

    public function edit(Shipment $shipment)
    {
        $patients = Patient::select('id', 'first_name', 'last_name', 'phone', 'address_1', 'address_2', 'city', 'state', 'pincode')
            ->orderBy('first_name')
            ->get();

        return view('pages.shipments.edit', compact('shipment', 'patients'));
    }

    public function update(Request $request, Shipment $shipment)
    {
        $validated = $request->validate([
            'recipient_name' => 'required|string|max:255',
            'recipient_phone' => 'required|string|max:20',
            'recipient_address' => 'required|string|max:500',
            'recipient_city' => 'required|string|max:100',
            'recipient_state' => 'required|string|max:100',
            'recipient_pincode' => 'required|string|max:10',
            'courier_name' => 'nullable|string|max:100',
            'tracking_number' => 'nullable|string|max:100|unique:shipments,tracking_number,' . $shipment->id,
            'items' => 'required|array|min:1',
            'remarks' => 'nullable|string|max:1000',
            'recipient_country' => 'required|string|max:100',
        ]);

        $shipment->update($validated);

        return redirect()->route('shipments.show', $shipment)
            ->with('success', 'Shipment updated successfully!');
    }

    // Quick status update (for dashboard/list)
    public function updateStatus(Request $request, Shipment $shipment)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,packed,dispatched,delivered,cancelled',
            'status_notes' => 'nullable|string|max:500',
        ]);

        $update = ['status' => $validated['status']];

        // Auto-set timestamps based on status
        if ($validated['status'] === 'packed' && !$shipment->packed_at) {
            $update['packed_at'] = now();
        }
        if ($validated['status'] === 'dispatched' && !$shipment->dispatched_at) {
            $update['dispatched_at'] = now();
        }
        if ($validated['status'] === 'delivered' && !$shipment->delivered_at) {
            $update['delivered_at'] = now();
        }

        if (!empty($validated['status_notes'])) {
            $update['status_notes'] = ($shipment->status_notes ? $shipment->status_notes . "\n" : '')
                . date('Y-m-d H:i') . ': ' . $validated['status_notes'];
        }

        $shipment->update($update);

        return redirect()->back()->with('success', "Status updated to {$shipment->getStatusLabel()}!");
    }

    public function destroy(Shipment $shipment)
    {
        $shipment->delete();
        return redirect()->route('shipments.index')
            ->with('success', 'Shipment deleted successfully!');
    }
}
