<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Payslip - {{ $payroll->fullname }}</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10.5px;
            color: #212529;
        }

        .page {
            padding: 20px 30px;
        }

        /* HEADER */
        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .header h4 {
            font-size: 14px;
            font-weight: bold;
        }

        .header small {
            font-size: 9px;
            color: #6c757d;
        }

        .header h5 {
            font-size: 11px;
            margin-top: 4px;
            text-transform: uppercase;
        }

        .header p {
            font-size: 9px;
        }

        hr {
            border: none;
            border-top: 1px solid #000;
            margin: 6px 0;
        }

        /* GRID */
        .row {
            width: 100%;
            margin-bottom: 6px;
        }

        .col {
            display: inline-block;
            width: 49%;
            vertical-align: top;
        }

        .col-right {
            float: right;
        }

        /* TABLE */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 3px 4px;
            font-size: 10px;
        }

        .text-right {
            text-align: right;
        }

        /* SECTION */
        .section-title {
            font-weight: bold;
            font-size: 11px;
            margin-bottom: 4px;
            border-bottom: 1px solid #ccc;
        }

        /* SALARY */
        .salary-table td {
            padding: 4px;
        }

        .row-total {
            font-weight: bold;
            background: #f2f2f2;
        }

        .row-earned {
            font-weight: bold;
        }

        /* NET */
        .net-box {
            background-color: #d1e7dd;
            color: #0f5132;
            margin-top: 8px;
            border: 1px solid #000;
        }

        .net-box td {
            text-align: center;
            font-weight: bold;
            font-size: 12px;
            padding: 8px;
        }

        /* OVERTIME */
        .overtime-table th,
        .overtime-table td {
            border: 1px solid #999;
            padding: 5px;
            font-size: 9.5px;
            text-align: center;
        }

        .overtime-table th {
            background: #eee;
        }

        /* ── TAX NOTE ── */
        .tax-note {
            color: #dc3545;
            font-size: 9px;
            margin-top: 5px;
            font-style: italic;
        }

        /* SIGNATURE */
        .signature {
            margin-top: 20px;
            width: 200px;
            float: right;
            text-align: center;
            font-size: 10px;
        }

        .signature img {
            margin: 6px 0;
        }

        .signature .name {
            font-weight: bold;
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="page">

        <!-- HEADER -->
        <div class="header">
            <h4>PT SALLAUNCH PRODUCTION</h4>
            <small>Culture Street No. 10, Jakarta | Phone: 021-123456</small>
            <hr>
            <h5>Employee Payslip</h5>
            <p>
                Period:
                {{ now()->startOfMonth()->format('d F Y') }} -
                {{ now()->endOfMonth()->format('d F Y') }}
            </p>
        </div>

        <!-- EMPLOYEE INFO -->
        <div class="row">
            <div class="col">
                <table>
                    <tr>
                        <td width="35%"><strong>Name</strong></td>
                        <td>: {{ $payroll->fullname }}</td>
                    </tr>
                    <tr>
                        <td><strong>Position</strong></td>
                        <td>: {{ $payroll->position }}</td>
                    </tr>
                </table>
            </div>

            <div class="col col-right">
                <table>
                    <tr>
                        <td width="35%"><strong>Grade</strong></td>
                        <td>: {{ $payroll->grade_name }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status</strong></td>
                        <td>: {{ ucfirst($payroll->employment_type) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <hr>

        <!-- EARNINGS & DEDUCTIONS -->
        <div class="row">

            <!-- LEFT -->
            <div class="col">
                <div class="section-title">Earnings</div>
                <table class="salary-table">
                    <tr>
                        <td>Basic Salary</td>
                        <td class="text-right">Rp {{ number_format($payroll->basic_salary, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Attendance</td>
                        <td class="text-right">
                            {{ $payroll->present_days }} / {{ $payroll->working_days_in_month }}
                        </td>
                    </tr>
                    <tr class="row-earned">
                        <td>Earned Salary</td>
                        <td class="text-right">Rp {{ number_format($payroll->earned_salary, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Overtime Pay</td>
                        <td class="text-right">Rp {{ number_format($payroll->overtime_pay, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="row-total">
                        <td>Total Earnings (A)</td>
                        <td class="text-right">Rp {{ number_format($payroll->total_salary, 0, ',', '.') }}</td>
                    </tr>
                </table>
            </div>

            <!-- RIGHT -->
            <div class="col col-right">
                <div class="section-title">Deductions</div>
                <table class="salary-table">
                    <tr>
                        <td>Tax (PPH21)</td>
                        <td class="text-right">Rp {{ number_format($payroll->tax_deduction ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>BPJS</td>
                        <td class="text-right">Rp {{ number_format($payroll->bpjs_deduction ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr class="row-total">
                        <td>Total (B)</td>
                        <td class="text-right">
                            Rp
                            {{ number_format(($payroll->tax_deduction ?? 0) + ($payroll->bpjs_deduction ?? 0), 0, ',', '.') }}
                        </td>
                    </tr>
                </table>
                <p class="tax-note">* Salaries above Rp 10.000.000 are subject to 5% PPH21 tax monthly. *</p>
            </div>

        </div>

        <hr>

        <!-- NET SALARY -->
        <table class="net-box">
            <tr>
                <td>
                    Net Salary = Rp {{ number_format($payroll->net_salary, 0, ',', '.') }}
                </td>
            </tr>
        </table>

        <!-- OVERTIME -->
        <div style="margin-top:10px;">
            <div class="section-title">Overtime Details</div>
            <table class="overtime-table">
                <thead>
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
                            <td class="text-right">Rp {{ number_format($ot->total_payment, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">No overtime</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- SIGNATURE -->
        <div class="signature">
            <p>Jakarta, {{ now()->format('d F Y') }}</p>
            <p><strong>HR Development</strong></p>
            @php
                $path = public_path('img/part/ttd.png');
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            @endphp

            <img src="{{ $base64 }}" width="80">
            <p class="name">Bimantarra</p>
        </div>

    </div>
</body>

</html>
