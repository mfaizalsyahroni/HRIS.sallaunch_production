@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-S...HASH..." crossorigin="anonymous" referrerpolicy="no-referrer" />


    {{-- Print-friendly CSS --}}
    <style>
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* Optional: perbaiki margin & padding saat print */
        @media print {
            body {
                margin: 1cm;
            }

            .card {
                box-shadow: none !important;
                border: 1px solid #000 !important;
            }

            .table-dark {
                background-color: #343a40 !important;
                color: #fff !important;
            }

            .table-light {
                background-color: #f8f9fa !important;
                color: #000 !important;
            }

            .alert-success {
                background-color: #d1e7dd !important;
                color: #0f5132 !important;
                border: 1px solid #0f5132 !important;
            }

            hr {
                border-top: 1px solid #000 !important;
            }

            /* Hide buttons or elements not needed on print */
            .no-print {
                display: none !important;
            }
        }
    </style>

    <div class="container mt-4">

        <div class="card shadow-lg p-4">

            {{-- HEADER --}}
            <div class="text-center mb-4">
                <h4 class="fw-bold mb-0">PT SALLAUNCH PRODUCTION</h4>
                <small class="text-muted">
                    Culture Street No. 10, Jakarta | Phone: 021-123456
                </small>

                <hr class="my-3">

                <h5 class="fw-bold text-uppercase">EMPLOYEE PAYSLIP</h5>
                <p class="mb-0">
                    Period:
                    {{ now()->startOfMonth()->format('d F Y') }}
                    - {{ now()->endOfMonth()->format('d F Y') }}
                </p>

            </div>


            {{-- @forelse ($payrolls as $row)
            @empty
            @endforelse --}}

            {{-- EMPLOYEE INFORMATION --}}
            <div class="row mb-1">
                <div class="col-md-6">
                    <table class="table table-borderless table-sm">
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
                    <table class="table table-borderless table-sm">
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

            {{-- INCOME & DEDUCTIONS --}}
            <div class="row">

                {{-- INCOME --}}
                <div class="col-md-6">
                    <h5 class="fw-bold mb-3">Earnings</h5>

                    <table class="table table-sm">
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

                {{-- DEDUCTIONS --}}
                <div class="col-md-6">
                    <h5 class="fw-bold mb-3">Deductions</h5>

                    <table class="table table-sm">
                        <tr>
                            <td>Tax (PPH21)</td>
                            <td class="text-end">
                                Rp {{ number_format($payroll->tax_deduction ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>

                        <tr>
                            <td>BPJS Contribution</td>
                            <td class="text-end">
                                Rp {{ number_format($payroll->bpjs_deduction ?? 0, 0, ',', '.') }}
                            </td>
                        </tr>

                        <tr class="fw-bold table-light">
                            <td>Total Deductions (B)</td>
                            <td class="text-end">
                                Rp
                                {{ number_format(($payroll->tax_deduction ?? 0) + ($payroll->bpjs_deduction ?? 0), 0, ',', '.') }}
                            </td>
                        </tr>
                    </table>
                    {{-- TAX NOTE --}}
                    <p class="mt-2 text-danger fw-semibold">
                        *Salaries above Rp 10.000.000 are taxed 5% PPH21 monthly.*
                    </p>
                </div>

            </div>

            <hr>

            {{-- NET SALARY --}}
            <div class="alert alert-success text-center fw-bold fs-5">
                Net Salary (A - B) =
                Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}
            </div>

            {{-- OVERTIME DETAILS --}}
            <h5 class="fw-bold mt-1">Overtime Details</h5>

            <div class="table-responsive">
                <table class="table table-bordered text-center align-middle">
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
                                    No overtime records this month
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- SIGNATURE --}}
            <div class="row mt-3">
                <div class="col-md-6"></div>

                <div class="col-md-6 text-end">
                    <p>Jakarta, {{ now()->format('d F Y') }}</p>

                    <p class="fw-bold mb-1">HR Development</p>

                    {{-- Signature Image --}}
                    <img src="{{ asset('img/part/ttd.png') }}" alt="Signature" width="100" class="mb-2 ms-4">

                    <p class="mb-0 me-3">Bimantarra</p>
                    <p class="fw-bold text-decoration-underline mb-0">
                        {{-- Optional line if needed --}}
                        {{-- ___________________ --}}
                    </p>
                </div>

            </div>

        </div>
    </div>
@endsection
