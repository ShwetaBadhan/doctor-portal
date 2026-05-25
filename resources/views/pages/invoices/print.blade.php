<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $invoice->invoice_number }}</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            font-size: 11px;
            color: #000;
            line-height: 1.4;
        }

        .page {
            position: relative;
            width: 210mm;
            min-height: 297mm;
        }

        /* LETTERHEAD BACKGROUND */
        .letterhead-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 210mm;
            height: 297mm;
            z-index: 0;
        }

        .letterhead-bg img {
            width: 100%;
            height: 100%;
            object-fit: fill;
        }

        /* CONTENT */
        .content {
            position: relative;
            z-index: 1;
            padding: 70mm 35mm 35mm 35mm; 
        }

        /* INVOICE HEADER */
        .invoice-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .company-info h3 {
            margin: 0 0 5px 0;
            font-size: 14px;
            color: #000;
        }

        .company-info p {
            margin: 2px 0;
            font-size: 10px;
        }

        .invoice-title {
            text-align: right;
        }

        .invoice-title h2 {
            margin: 0 0 5px 0;
            font-size: 18px;
            font-weight: bold;
        }

        .invoice-title p {
            margin: 2px 0;
            font-size: 10px;
        }

        /* BILL TO */
        .bill-to {
            margin-bottom: 15px;
            font-size: 11px;
        }

        .bill-to strong {
            font-size: 12px;
        }

        /* ITEMS TABLE */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 10px;
        }

        .items-table th,
        .items-table td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }

        .items-table th {
            background: rgba(240, 240, 240, 0.9);
            font-weight: bold;
        }

        .items-table .text-right {
            text-align: right;
        }

        /* TOTALS TABLE */
        .totals-table {
            width: 280px;
            margin-left: auto;
            font-size: 11px;
        }

        .totals-table td {
            padding: 4px 8px;
        }

        .totals-table .total-row {
            font-weight: bold;
            font-size: 12px;
            border-top: 2px solid #000;
        }

        /* FOOTER */
        .invoice-footer {
            margin-top: 20px;
            font-size: 10px;
            color: #333;
        }

        .invoice-footer p {
            margin: 3px 0;
        }

        /* SIGNATURE */
        .signature {
            margin-top: 30px;
            text-align: right;
            padding-right: 20mm;
            font-size: 11px;
        }

        .signature-line {
            border-top: 1px solid #000;
            width: 150px;
            margin: 5px auto 2px auto;
        }
    </style>
</head>

<body>
    <div class="page">
        <!-- LETTERHEAD BACKGROUND -->
        @if($letterheadBase64)
            <div class="letterhead-bg">
                <img src="data:image/{{ $imageType }};base64,{{ $letterheadBase64 }}" alt="">
            </div>
        @endif

        <!-- CONTENT -->
        <div class="content">
            
            <!-- HEADER -->
            <div class="invoice-header">
              
                <div class="invoice-title">
                    <h2>TAX INVOICE</h2>
                    <p><strong>No:</strong> {{ $invoice->invoice_number }}</p>
                    <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($invoice->invoice_date)->format('d M Y') }}</p>
                </div>
            </div>

            <!-- BILL TO -->
            <div class="bill-to">
                <strong>Bill To:</strong><br>
                {{ $invoice->patient_name }}<br>
                @if($invoice->patient_mobile)
                    Mobile: {{ $invoice->patient_mobile }}<br>
                @endif
                @if($invoice->patient_address)
                    {!! nl2br(e($invoice->patient_address)) !!}
                @endif
            </div>

            <!-- ITEMS TABLE -->
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 5%;">#</th>
                        <th style="width: 40%;">Item</th>
                        <th style="width: 10%;">HSN</th>
                        <th style="width: 10%;" class="text-right">Qty</th>
                        <th style="width: 15%;" class="text-right">Tax</th>
                        <th style="width: 20%;" class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->items as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ $item['hsn'] ?? '-' }}</td>
                        <td class="text-right">{{ $item['quantity'] }}</td>
                        <td class="text-right">
                            @if(isset($item['tax_type']) && $item['tax_type'] !== 'NONE')
                                {{ $item['tax_type'] }}
                                @if(isset($item['tax_percent']) && $item['tax_percent'])
                                    ({{ $item['tax_percent'] }}%)
                                @endif
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-right">
                            Rs.{{ number_format(($item['amount'] ?? 0) + ($item['tax_amount'] ?? 0), 2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- TOTALS -->
            <table class="totals-table">
                <tr>
                    <td>Taxable Amount:</td>
                    <td class="text-right">Rs.{{ number_format($invoice->taxable_amount ?? 0, 2) }}</td>
                </tr>
                @if(($invoice->igst_amount ?? 0) > 0)
                <tr>
                    <td>IGST:</td>
                    <td class="text-right">Rs.{{ number_format($invoice->igst_amount, 2) }}</td>
                </tr>
                @endif
                @if(($invoice->cgst_amount ?? 0) > 0)
                <tr>
                    <td>CGST:</td>
                    <td class="text-right">Rs.{{ number_format($invoice->cgst_amount, 2) }}</td>
                </tr>
                <tr>
                    <td>SGST:</td>
                    <td class="text-right">Rs.{{ number_format($invoice->sgst_amount, 2) }}</td>
                </tr>
                @endif
                <tr class="total-row">
                    <td><strong>Grand Total:</strong></td>
                    <td class="text-right"><strong>Rs.{{ number_format($invoice->total_amount ?? 0, 2) }}</strong></td>
                </tr>
            </table>

            <!-- FOOTER NOTES -->
            @if($invoice->terms || $invoice->notes)
            <div class="invoice-footer">
                @if($invoice->terms)
                    <p><strong>Terms:</strong> {{ $invoice->terms }}</p>
                @endif
                @if($invoice->notes)
                    <p><strong>Notes:</strong> {{ $invoice->notes }}</p>
                @endif
            </div>
            @endif

            <!-- SIGNATURE -->
            <div class="signature">
                <div class="signature-line"></div>
                Authorized Signatory<br>
                {{ $invoice->company_name }}
            </div>

            <!-- GENERATED AT (small) -->
            <div style="margin-top: 15px; text-align: center; font-size: 9px; color: #666;">
                Generated on: {{ $generatedAt->format('d-m-Y H:i:s') }}
            </div>

        </div>
    </div>
</body>
</html>