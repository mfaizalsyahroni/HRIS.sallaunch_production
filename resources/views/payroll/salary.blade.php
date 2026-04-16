@if (!isset($is_pdf))
    @extends('layouts.app')
@endif

@section('content')
    @if (!isset($is_pdf))
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @endif

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        * {
            box-sizing: border-box;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 6px;
            vertical-align: middle;
        }

        /* TEXT */
        .text-end {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .fw-bold {
            font-weight: bold;
        }

        .text-primary {
            color: #0d6efd;
        }

        .text-danger {
            color: #dc3545;
        }

        .text-muted {
            color: #6c757d;
        }

        /* TABLE */
        .table-sm td {
            padding: 4px;
        }

        .table-bordered td,
        .table-bordered th {
            border: 1px solid #000;
        }

        .table-light {
            background: #f2f2f2;
        }

        .table-secondary {
            background: #e9ecef;
        }

        /* GRID FIX */
        .row {
            width: 100%;
            display: block;
            clear: both;
        }

        .col-md-6 {
            width: 48%;
            float: left;
        }

        .col-md-6:last-child {
            float: right;
        }

        /* SPACING */
        .mt-4 {
            margin-top: 15px;
        }

        .mb-4 {
            margin-bottom: 15px;
        }

        .mb-3 {
            margin-bottom: 10px;
        }

        .mb-1 {
            margin-bottom: 5px;
        }

        .mt-3 {
            margin-top: 10px;
        }

        .mt-1 {
            margin-top: 5px;
        }

        hr {
            margin: 10px 0;
            border: 0;
            border-top: 1px solid #000;
        }

        /* ALERT FIX */
        .alert {
            display: inline-block;
            width: 100%;
            padding: 10px;
            border: 1px solid #28a745;
            background: #d4edda;
        }

        /* PREVENT BREAK */
        .no-break {
            page-break-inside: avoid;
        }

        .table-responsive {
            width: 100%;
            overflow: hidden;
        }
    </style>

    <div class="container mt-4">

        <div class="p-4">

            {{-- HEADER --}}
            <div class="text-center mb-4">
                <h4 class="fw-bold mb-0">PT SALLAUNCH PRODUCTION</h4>
                <small class="text-muted">
                    Culture Street No. 10, Jakarta | Phone: 021-123456
                </small>

                <hr>

                <h5 class="fw-bold">EMPLOYEE PAYSLIP</h5>
                <p class="mb-0">
                    Period:
                    {{ now()->startOfMonth()->format('d F Y') }}
                    - {{ now()->endOfMonth()->format('d F Y') }}
                </p>
            </div>

            {{-- EMPLOYEE INFO --}}
            <div class="row mb-1">
                <div class="col-md-6">
                    <table class="table-sm">
                        <tr>
                            <td width="35%">Name</td>
                            <td>: {{ $payroll->fullname }}</td>
                        </tr>
                        <tr>
                            <td>Position</td>
                            <td>: {{ $payroll->position }}</td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <table class="table-sm">
                        <tr>
                            <td width="35%">Grade</td>
                            <td>: {{ $payroll->grade_name }}</td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td>: {{ ucfirst($payroll->employment_type) }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <hr>

            {{-- EARNINGS & DEDUCTIONS --}}
            <div class="row no-break">

                <div class="col-md-6">
                    <h5 class="fw-bold mb-3">Earnings</h5>

                    <table class="table-sm">
                        <tr>
                            <td>Basic Salary</td>
                            <td class="text-end">
                                Rp {{ number_format($payroll->basic_salary, 0, ',', '.') }}
                            </td>
                        </tr>

                        <tr>
                            <td>Attendance</td>
                            <td class="text-end">
                                {{ $payroll->present_days }} / {{ $payroll->working_days_in_month }} days
                            </td>
                        </tr>

                        <tr class="fw-bold text-primary">
                            <td>Earned Salary</td>
                            <td class="text-end">
                                Rp {{ number_format($payroll->earned_salary, 0, ',', '.') }}
                            </td>
                        </tr>

                        <tr>
                            <td>Overtime Pay</td>
                            <td class="text-end">
                                Rp {{ number_format($payroll->overtime_pay, 0, ',', '.') }}
                            </td>
                        </tr>

                        <tr class="fw-bold table-light">
                            <td>Total (A)</td>
                            <td class="text-end">
                                Rp {{ number_format($payroll->total_salary) }}
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="col-md-6">
                    <h5 class="fw-bold mb-3">Deductions</h5>

                    <table class="table-sm">
                        <tr>
                            <td>Tax (PPH21)</td>
                            <td class="text-end">
                                Rp {{ number_format($payroll->tax_deduction ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>

                        <tr>
                            <td>BPJS</td>
                            <td class="text-end">
                                Rp {{ number_format($payroll->bpjs_deduction ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>

                        <tr class="fw-bold table-light">
                            <td>Total (B)</td>
                            <td class="text-end">
                                Rp
                                {{ number_format(($payroll->tax_deduction ?? 0) + ($payroll->bpjs_deduction ?? 0), 0, ',', '.') }}
                            </td>
                        </tr>
                    </table>

                    <p class="mt-1 text-danger">
                        *Salary above Rp 10.000.000 taxed 5%*
                    </p>
                </div>

            </div>

            <hr>

            {{-- NET SALARY (FIX TOTAL) --}}
            <table style="margin-top:10px;">
                <tr>
                    <td class="text-center fw-bold" style="border:1px solid #28a745; background:#d4edda; padding:10px;">
                        Net Salary (A - B) =
                        Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}
                    </td>
                </tr>
            </table>

            {{-- OVERTIME --}}
            <h5 class="fw-bold mt-3">Overtime Details</h5>

            <table class="table-bordered text-center no-break">
                <thead class="table-secondary">
                    <tr>
                        <th>Date</th>
                        <th>Hours</th>
                        <th>Payment</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payroll->overtimes as $ot)
                        <tr>
                            <td>{{ $ot->formatted_overtime_date }}</td>
                            <td>{{ $ot->actual_hour_minute }}</td>
                            <td class="text-end">
                                Rp {{ number_format($ot->total_payment, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-muted">
                                No overtime records
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- SIGNATURE --}}
            <div class="row mt-3">
                <div class="col-md-6"></div>

                <div class="col-md-6 text-end">
                    <p>Jakarta, {{ now()->format('d F Y') }}</p>

                    <p class="fw-bold mb-1">HR Development</p>

                    <img src="{{ public_path('img/part/ttd.png') }}" width="80">

                    <p>Bimantarra</p>
                </div>
            </div>

        </div>
    </div>

    @if (!isset($is_pdf))
        <div class="text-center mt-4">
            <form action="{{ route('payroll.preview') }}" method="POST">
                @csrf
                <button type="submit">
                    Download PDF
                </button>
            </form>
        </div>
    @endif
@endsection
