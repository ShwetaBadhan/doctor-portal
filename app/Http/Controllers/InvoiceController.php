<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::with('patient');

        // 🔍 Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'LIKE', "%{$search}%")
                    ->orWhere('patient_name', 'LIKE', "%{$search}%")
                    ->orWhere('patient_mobile', 'LIKE', "%{$search}%")
                    ->orWhereHas('patient', function ($sub) use ($search) {
                        // Search by patient's unique ID (e.g., PAT000001)
                        $sub->where('patient_id', 'LIKE', "%{$search}%");
                    });
            });
        }

        // 📅 From Date filter
        if ($request->filled('from_date')) {
            $query->whereDate('invoice_date', '>=', $request->from_date);
        }

        // 📅 To Date filter
        if ($request->filled('to_date')) {
            $query->whereDate('invoice_date', '<=', $request->to_date);
        }

        // 💰 Status filter (paid/unpaid)
        if ($request->filled('status')) {
            if ($request->status === 'paid') {
                $query->where('is_paid', true);
            } elseif ($request->status === 'unpaid') {
                $query->where('is_paid', false);
            }
        }

        // Get results
        $invoices = $query->latest('invoice_date')->paginate(15);

        // Debug: Log the query results (remove in production)
        Log::info('Invoice filters', [
            'search' => $request->search,
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
            'status' => $request->status,
            'count' => $invoices->count()
        ]);

        return view('pages.invoices.index', compact('invoices'));
    }

    public function create()
    {
        $patients = Patient::select('id', 'first_name', 'last_name', 'phone', 'address_1', 'address_2', 'city', 'state', 'pincode')
            ->orderBy('first_name')
            ->get();

        $nextNumber = Invoice::generateInvoiceNumber();

        // Company defaults (from your PDF)
        $company = [
            'name' => 'E-Bio-Cares',
            'address' => 'VPO PHOOLPUR 144026, NEAR LAMBRA JALANDHAR',
            'gstin' => '03BHTPS6858P1Z4',
            'pan' => 'BHTPS6858P',
            'contact' => '98720-01445, 180012301445',
            'website' => 'www.ebiocares.in'
        ];

        return view('pages.invoices.create', compact('patients', 'nextNumber', 'company'));
    }

    public function store(Request $request)
    {
        Log::info('Invoice Store Request', [
            'patient_id' => $request->patient_id,
            'patient_name' => $request->patient_name,
            'all_input' => $request->all()
        ]);

        $validated = $request->validate([
            'invoice_date' => 'required|date',
            'patient_id' => 'nullable|exists:patients,patient_id',
            'patient_name' => 'required|string|max:255',
            'patient_mobile' => 'nullable|string|max:20',
            'patient_address' => 'nullable|string|max:500',
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string|max:255',
            'items.*.hsn' => 'nullable|string|max:50',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.rate' => 'required|numeric|min:0',
            'items.*.tax_type' => 'required|in:IGST,CGST+SGST,NONE',
            'items.*.tax_percent' => 'nullable|numeric|min:0|max:100',
            'terms' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Calculate totals
        $items = collect($validated['items'])->map(function ($item) {
            $amount = $item['quantity'] * $item['rate'];
            $tax = 0;

            if ($item['tax_type'] === 'IGST' && !empty($item['tax_percent'])) {
                $tax = ($amount * $item['tax_percent']) / 100;
            } elseif ($item['tax_type'] === 'CGST+SGST' && !empty($item['tax_percent'])) {
                $tax = ($amount * $item['tax_percent']) / 100; // Total tax (split later)
            }

            return [
                'name' => $item['name'],
                'hsn' => $item['hsn'] ?? null,
                'quantity' => $item['quantity'],
                'rate' => $item['rate'],
                'tax_type' => $item['tax_type'],
                'tax_percent' => $item['tax_percent'] ?? 0,
                'amount' => round($amount, 2),
                'tax_amount' => round($tax, 2),
            ];
        });

        $taxableAmount = $items->sum('amount');
        $igstTotal = $items->where('tax_type', 'IGST')->sum('tax_amount');
        $cgstSgstTotal = $items->where('tax_type', 'CGST+SGST')->sum('tax_amount');
        $cgstTotal = $cgstSgstTotal / 2;
        $sgstTotal = $cgstSgstTotal / 2;
        $totalAmount = $taxableAmount + $igstTotal + $cgstTotal + $sgstTotal;

        $invoice = Invoice::create([
            'invoice_number' => Invoice::generateInvoiceNumber(),
            'invoice_date' => $validated['invoice_date'],
            'patient_id' => $validated['patient_id'] ?? null,
            'patient_name' => $validated['patient_name'],
            'patient_mobile' => $validated['patient_mobile'] ?? null,
            'patient_address' => $validated['patient_address'] ?? null,
            'company_name' => 'E-Bio-Cares',
            'company_address' => 'VPO PHOOLPUR 144026, NEAR LAMBRA JALANDHAR',
            'gstin' => '03BHTPS6858P1Z4',
            'pan' => 'BHTPS6858P',
            'contact' => '98720-01445, 180012301445',
            'items' => $items->all(),
            'taxable_amount' => round($taxableAmount, 2),
            'igst_amount' => round($igstTotal, 2),
            'cgst_amount' => round($cgstTotal, 2),
            'sgst_amount' => round($sgstTotal, 2),
            'total_amount' => round($totalAmount, 2),
            'terms' => $validated['terms'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('invoices.print', $invoice)
            ->with('success', 'Invoice created successfully!');
    }

    public function show(Invoice $invoice)
    {
        return view('pages.invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $patients = Patient::select('id', 'first_name', 'last_name', 'phone', 'address_1', 'address_2', 'city', 'state', 'pincode')
            ->orderBy('first_name')
            ->get();

        $company = [
            'name' => 'E-Bio-Cares',
            'address' => 'VPO PHOOLPUR 144026, NEAR LAMBRA JALANDHAR',
            'gstin' => '03BHTPS6858P1Z4',
            'pan' => 'BHTPS6858P',
            'contact' => '98720-01445, 180012301445',
            'website' => 'www.ebiocares.in'
        ];

        // ✅ FIXED: Correct view path
        return view('pages.invoices.edit', compact('invoice', 'patients', 'company'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'invoice_date' => 'required|date',
            'patient_id' => 'nullable|exists:patients,patient_id',
            'patient_name' => 'required|string|max:255',
            'patient_mobile' => 'nullable|string|max:20',
            'patient_address' => 'nullable|string|max:500',
            'items' => 'required|array|min:1',
            'items.*.name' => 'required|string|max:255',
            'items.*.hsn' => 'nullable|string|max:50',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.rate' => 'required|numeric|min:0',
            'items.*.tax_type' => 'required|in:IGST,CGST+SGST,NONE',
            'items.*.tax_percent' => 'nullable|numeric|min:0|max:100',
            'terms' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Calculate totals (same as store)
        $items = collect($validated['items'])->map(function ($item) {
            $amount = $item['quantity'] * $item['rate'];
            $tax = 0;

            if ($item['tax_type'] === 'IGST' && !empty($item['tax_percent'])) {
                $tax = ($amount * $item['tax_percent']) / 100;
            } elseif ($item['tax_type'] === 'CGST+SGST' && !empty($item['tax_percent'])) {
                $tax = ($amount * $item['tax_percent']) / 100;
            }

            return [
                'name' => $item['name'],
                'hsn' => $item['hsn'] ?? null,
                'quantity' => $item['quantity'],
                'rate' => $item['rate'],
                'tax_type' => $item['tax_type'],
                'tax_percent' => $item['tax_percent'] ?? 0,
                'amount' => round($amount, 2),
                'tax_amount' => round($tax, 2),
            ];
        });

        $taxableAmount = $items->sum('amount');
        $igstTotal = $items->where('tax_type', 'IGST')->sum('tax_amount');
        $cgstSgstTotal = $items->where('tax_type', 'CGST+SGST')->sum('tax_amount');
        $cgstTotal = $cgstSgstTotal / 2;
        $sgstTotal = $cgstSgstTotal / 2;
        $totalAmount = $taxableAmount + $igstTotal + $cgstTotal + $sgstTotal;

        // Update invoice
        $invoice->update([
            'invoice_date' => $validated['invoice_date'],
            'patient_id' => $validated['patient_id'] ?? null,
            'patient_name' => $validated['patient_name'],
            'patient_mobile' => $validated['patient_mobile'] ?? null,
            'patient_address' => $validated['patient_address'] ?? null,
            'items' => $items->all(),
            'taxable_amount' => round($taxableAmount, 2),
            'igst_amount' => round($igstTotal, 2),
            'cgst_amount' => round($cgstTotal, 2),
            'sgst_amount' => round($sgstTotal, 2),
            'total_amount' => round($totalAmount, 2),
            'terms' => $validated['terms'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('invoices.print', $invoice)
            ->with('success', 'Invoice updated successfully!');
    }
    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('invoices.index')
            ->with('success', 'Invoice deleted successfully!');
    }

    // Print view (clean, printer-friendly)
    public function print(Invoice $invoice)
    {
        return view('pages.invoices.print', compact('invoice'));
    }
}
