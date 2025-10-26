@extends('layouts.app')

@section('title', 'Payroll Slip')
@section('page-title', 'Payroll Slip')

@section('content')
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('payroll.index') }}">Payroll</a></li>
                <li class="breadcrumb-item active">Slip</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-10 mx-auto">
            <div class="table-card" id="payroll-slip">
                <!-- Header -->
                <div class="text-center border-bottom pb-4 mb-4">
                    <h3 class="mb-1">PAYROLL SLIP</h3>
                    <p class="text-muted mb-0">For the month of <strong>{{ date('F', mktime(0, 0, 0, $payroll->month, 1)) }} {{ $payroll->year }}</strong></p>
                </div>

                <!-- Employee Details -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Employee Information</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th width="40%">Name:</th>
                                <td>{{ $payroll->employee->full_name }}</td>
                            </tr>
                            <tr>
                                <th>Employee Code:</th>
                                <td>{{ $payroll->employee->employee_code }}</td>
                            </tr>
                            <tr>
                                <th>Department:</th>
                                <td>{{ $payroll->employee->department->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Position:</th>
                                <td>{{ $payroll->employee->position->name ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Joining Date:</th>
                                <td>{{ $payroll->employee->joining_date?->format('M d, Y') ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-3">Payment Information</h6>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <th width="40%">Pay Period:</th>
                                <td>{{ date('F', mktime(0, 0, 0, $payroll->month, 1)) }} {{ $payroll->year }}</td>
                            </tr>
                            <tr>
                                <th>Working Days:</th>
                                <td>{{ $payroll->working_days ?? 26 }}</td>
                            </tr>
                            <tr>
                                <th>Payment Date:</th>
                                <td>{{ $payroll->payment_date?->format('M d, Y') ?? 'Pending' }}</td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td>
                                    <span class="badge badge-status-{{ $payroll->status }}">
                                        {{ ucfirst(str_replace('_', ' ', $payroll->status)) }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Salary Breakdown -->
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card border-success">
                            <div class="card-header bg-success text-white">
                                <h6 class="mb-0"><i class="bi bi-plus-circle me-2"></i>Earnings</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm mb-0">
                                    <tr>
                                        <td>Basic Salary</td>
                                        <td class="text-end">₹{{ number_format($payroll->basic_salary, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>HRA</td>
                                        <td class="text-end">₹{{ number_format($payroll->hra ?? 0, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>DA</td>
                                        <td class="text-end">₹{{ number_format($payroll->da ?? 0, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Medical Allowance</td>
                                        <td class="text-end">₹{{ number_format($payroll->medical_allowance ?? 0, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Transport Allowance</td>
                                        <td class="text-end">₹{{ number_format($payroll->transport_allowance ?? 0, 2) }}</td>
                                    </tr>
                                    @if($payroll->other_allowance)
                                        <tr>
                                            <td>Other Allowances</td>
                                            <td class="text-end">₹{{ number_format($payroll->other_allowance, 2) }}</td>
                                        </tr>
                                    @endif
                                    <tr class="border-top fw-bold">
                                        <td>Total Earnings</td>
                                        <td class="text-end">₹{{ number_format($payroll->gross_salary, 2) }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <div class="card border-danger">
                            <div class="card-header bg-danger text-white">
                                <h6 class="mb-0"><i class="bi bi-dash-circle me-2"></i>Deductions</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-sm mb-0">
                                    <tr>
                                        <td>PF (Provident Fund)</td>
                                        <td class="text-end">₹{{ number_format($payroll->pf ?? 0, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>ESI</td>
                                        <td class="text-end">₹{{ number_format($payroll->esi ?? 0, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>TDS</td>
                                        <td class="text-end">₹{{ number_format($payroll->tds ?? 0, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Loan</td>
                                        <td class="text-end">₹{{ number_format($payroll->loan ?? 0, 2) }}</td>
                                    </tr>
                                    @if($payroll->other_deduction)
                                        <tr>
                                            <td>Other Deductions</td>
                                            <td class="text-end">₹{{ number_format($payroll->other_deduction, 2) }}</td>
                                        </tr>
                                    @endif
                                    <tr class="border-top fw-bold">
                                        <td>Total Deductions</td>
                                        <td class="text-end">₹{{ number_format($payroll->deductions, 2) }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Net Salary -->
                <div class="alert alert-success text-center py-4">
                    <h5 class="mb-2">Net Salary Payable</h5>
                    <h2 class="mb-0 text-success">₹{{ number_format($payroll->net_salary, 2) }}</h2>
                    <small class="text-muted">
                        (In Words: {{ ucwords(\Illuminate\Support\Str::title(numberToWords($payroll->net_salary))) }} Only)
                    </small>
                </div>

                @if($payroll->notes)
                    <div class="border-top pt-3">
                        <h6 class="text-muted mb-2">Notes:</h6>
                        <p class="mb-0">{{ $payroll->notes }}</p>
                    </div>
                @endif

                <!-- Footer -->
                <div class="border-top mt-4 pt-4">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-0 text-muted small">
                                <i class="bi bi-info-circle me-1"></i>
                                This is a system-generated payroll slip
                            </p>
                        </div>
                        <div class="col-md-6 text-end">
                            <p class="mb-0 text-muted small">
                                Generated on: {{ now()->format('M d, Y H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="text-center mt-4">
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="bi bi-printer"></i> Print Slip
                </button>
                <button onclick="downloadPDF()" class="btn btn-success">
                    <i class="bi bi-download"></i> Download PDF
                </button>
                @if($payroll->status == 'pending')
                    <form action="{{ route('payroll.markPaid', $payroll) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle"></i> Mark as Paid
                        </button>
                    </form>
                @endif
                <a href="{{ route('payroll.index') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    @media print {
        .btn, .breadcrumb, nav, .sidebar, .navbar {
            display: none !important;
        }
        .table-card {
            box-shadow: none !important;
            border: 1px solid #dee2e6 !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    function downloadPDF() {
        // This would integrate with a PDF generation library or backend endpoint
        alert('PDF download functionality will be implemented with proper PDF library integration');
    }
</script>
@endpush

@php
function numberToWords($number) {
    $words = [
        0 => 'zero', 1 => 'one', 2 => 'two', 3 => 'three', 4 => 'four',
        5 => 'five', 6 => 'six', 7 => 'seven', 8 => 'eight', 9 => 'nine',
        10 => 'ten', 11 => 'eleven', 12 => 'twelve', 13 => 'thirteen',
        14 => 'fourteen', 15 => 'fifteen', 16 => 'sixteen', 17 => 'seventeen',
        18 => 'eighteen', 19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
        40 => 'forty', 50 => 'fifty', 60 => 'sixty', 70 => 'seventy',
        80 => 'eighty', 90 => 'ninety'
    ];

    if ($number < 21) {
        return $words[$number];
    } elseif ($number < 100) {
        $tens = (int)($number / 10) * 10;
        $units = $number % 10;
        return $words[$tens] . ($units ? ' ' . $words[$units] : '');
    } elseif ($number < 1000) {
        $hundreds = (int)($number / 100);
        $remainder = $number % 100;
        return $words[$hundreds] . ' hundred' . ($remainder ? ' ' . numberToWords($remainder) : '');
    } elseif ($number < 100000) {
        $thousands = (int)($number / 1000);
        $remainder = $number % 1000;
        return numberToWords($thousands) . ' thousand' . ($remainder ? ' ' . numberToWords($remainder) : '');
    } else {
        return number_format($number, 2);
    }
}
@endphp
