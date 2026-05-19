<!DOCTYPE html>
<html>
<head>
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 20px; }
        .header { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .company h3 { margin: 0 0 5px 0; color: #2E37A4; }
        .invoice-title { text-align: right; }
        .invoice-title h2 { margin: 0; color: #2E37A4; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f8f9fa; }
        .text-right { text-align: right; }
        .total-row { font-weight: bold; }
        .footer { margin-top: 30px; font-size: 10px; color: #666; }
        @media print {
            .no-print { display: none; }
            body { margin: 10mm; }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company">
            <h3>{{ $invoice->company_name }}</h3>
            <p>{{ $invoice->company_address }}<br>
            GSTIN: {{ $invoice->gstin }} | PAN: {{ $invoice->pan }}<br>
            Contact: {{ $invoice->contact }}</p>
        </div>
        <div class="invoice-title">
            <h2>INVOICE</h2>
            <p><strong>No:</strong> {{ $invoice->invoice_number }}<br>
            <strong>Date:</strong> {{ $invoice->invoice_date->format('d M Y') }}</p>
        </div>
    </div>

    <div style="margin-bottom: 20px;">
        <strong>Bill To:</strong><br>
        {{ $invoice->patient_name }}<br>
        @if($invoice->patient_mobile) Mobile: {{ $invoice->patient_mobile }}<br>@endif
        @if($invoice->patient_address) {{ nl2br($invoice->patient_address) }}@endif
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Item</th>
                <th>HSN</th>
                <th class="text-right">Qty</th>
                <th class="text-right">Rate</th>
                <th class="text-right">Tax</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item['name'] }}</td>
                <td>{{ $item['hsn'] ?? '-' }}</td>
                <td class="text-right">{{ $item['quantity'] }}</td>
                <td class="text-right">₹{{ number_format($item['rate'], 2) }}</td>
                <td class="text-right">
                    @if($item['tax_type'] !== 'NONE')
                        {{ $item['tax_type'] }} @if($item['tax_percent'])({{ $item['tax_percent'] }}%)@endif
                    @else
                        -
                    @endif
                </td>
                <td class="text-right">₹{{ number_format($item['amount'] + $item['tax_amount'], 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <table style="width: 300px; margin-left: auto;">
        <tr>
            <td>Taxable Amount:</td>
            <td class="text-right">₹{{ number_format($invoice->taxable_amount, 2) }}</td>
        </tr>
        @if($invoice->igst_amount > 0)
        <tr>
            <td>IGST:</td>
            <td class="text-right">₹{{ number_format($invoice->igst_amount, 2) }}</td>
        </tr>
        @endif
        @if($invoice->cgst_amount > 0)
        <tr>
            <td>CGST:</td>
            <td class="text-right">₹{{ number_format($invoice->cgst_amount, 2) }}</td>
        </tr>
        <tr>
            <td>SGST:</td>
            <td class="text-right">₹{{ number_format($invoice->sgst_amount, 2) }}</td>
        </tr>
        @endif
        <tr class="total-row">
            <td><strong>Total:</strong></td>
            <td class="text-right"><strong>₹{{ number_format($invoice->total_amount, 2) }}</strong></td>
        </tr>
    </table>

    @if($invoice->terms || $invoice->notes)
    <div class="footer">
        @if($invoice->terms)
        <p><strong>Terms:</strong> {{ $invoice->terms }}</p>
        @endif
        @if($invoice->notes)
        <p><strong>Notes:</strong> {{ $invoice->notes }}</p>
        @endif
    </div>
    @endif

    <div class="no-print" style="margin-top: 20px; text-align: center;">
        <button onclick="window.print()" class="btn btn-primary">🖨️ Print Invoice</button>
        <a href="{{ route('invoices.index') }}" class="btn btn-light">← Back to Invoices</a>
    </div>
</body>
</html>