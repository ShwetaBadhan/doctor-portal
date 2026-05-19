@extends('layout.master')

@section('content')
<div class="page-wrapper">
    <div class="content">
        
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h6 class="fw-bold mb-0">
                <i class="ti ti-file-invoice me-2 text-primary"></i>Invoices
            </h6>
            @can('create-invoices')
                 <a href="{{ route('invoices.create') }}" class="btn btn-primary">
                <i class="ti ti-plus me-1"></i> Create Invoice
            </a>
            @endcan
           
        </div>

        <!-- SweetAlert Messages -->
        @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: @json(session('success')),
                        timer: 3000,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end'
                    });
                });
            </script>
        @endif

        <!-- Search & Filter -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('invoices.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Search invoice # or patient..." 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="from_date" class="form-control" 
                               value="{{ request('from_date') }}" placeholder="From Date">
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="to_date" class="form-control" 
                               value="{{ request('to_date') }}" placeholder="To Date">
                    </div>
                    <div class="col-md-2">
                        <select name="status" class="select">
                            <option value="">All Status</option>
                            <option value="paid" {{ request('status')=='paid'?'selected':'' }}>Paid</option>
                            <option value="unpaid" {{ request('status')=='unpaid'?'selected':'' }}>Unpaid</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ti ti-search me-1"></i> Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Invoices Table -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Invoice #</th>
                                <th>Patient</th>
                                <th>Date</th>
                                <th class="text-end">Amount</th>
                                <th>Tax</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invoices as $invoice)
                            <tr>
                                <td>
                                    <a href="{{ route('invoices.print', $invoice) }}" 
                                       class="fw-medium text-primary" target="_blank">
                                        {{ $invoice->invoice_number }}
                                    </a>
                                </td>
                                <td>
                                    <div class="fw-medium">{{ $invoice->patient_name }}</div>
                                    @if($invoice->patient_mobile)
                                        <small class="text-muted">{{ $invoice->patient_mobile }}</small>
                                    @endif
                                </td>
                                <td>{{ $invoice->invoice_date->format('d M Y') }}</td>
                                <td class="text-end fw-medium">₹{{ number_format($invoice->total_amount, 2) }}</td>
                                <td>
                                    @if($invoice->igst_amount > 0)
                                        <small class="text-danger">IGST: ₹{{ number_format($invoice->igst_amount, 2) }}</small>
                                    @elseif($invoice->cgst_amount > 0)
                                        <small class="text-success">CGST+SGST: ₹{{ number_format($invoice->cgst_amount + $invoice->sgst_amount, 2) }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-{{ $invoice->is_paid ? 'success' : 'warning' }} bg-opacity-10 text-{{ $invoice->is_paid ? 'success' : 'warning' }} border border-{{ $invoice->is_paid ? 'success' : 'warning' }}">
                                        {{ $invoice->is_paid ? 'Paid' : 'Unpaid' }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm">
                                    @can('print-invoices')
                                        <a href="{{ route('invoices.print', $invoice) }}" 
                                           class="btn btn-light" target="_blank" title="Print">
                                            <i class="ti ti-printer"></i>
                                        </a>
                                    @endcan
                                         @can('edit-invoices')
                                        <a href="{{ route('invoices.edit', $invoice) }}" 
                                           class="btn btn-light" title="Edit">
                                            <i class="ti ti-edit"></i>
                                        </a>
                                        @endcan
                                         @can('delete-invoices')
                                        <button type="button" class="btn btn-light text-danger" 
                                                onclick="confirmDelete({{ $invoice->id }}, '{{ $invoice->invoice_number }}')" 
                                                title="Delete">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="ti ti-file-invoice fs-1 mb-3 d-block"></i>
                                    <p>No invoices found.</p>
                                    <a href="{{ route('invoices.create') }}" class="btn btn-primary btn-sm">
                                        <i class="ti ti-plus me-1"></i> Create First Invoice
                                    </a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($invoices->hasPages())
                <div class="mt-3">
                    {{ $invoices->withQueryString()->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id, invoiceNumber) {
    Swal.fire({
        title: 'Delete Invoice?',
        html: `Are you sure you want to delete invoice <strong>${invoiceNumber}</strong>?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#dc3545'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/invoices/${id}`;
            form.innerHTML = `@csrf @method('DELETE')`;
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endsection