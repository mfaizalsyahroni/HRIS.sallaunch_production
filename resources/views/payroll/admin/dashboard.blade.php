@extends('layouts.app')

@section('content')
    <div class="container mt-4">

        {{-- Bootstrap + FontAwesome --}}
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
            crossorigin="anonymous" />

        {{-- TITLE --}}
        <h2 class="mb-4 text-center fw-bold">
            Dashboard Payroll (Admin)
        </h2>

        {{-- PERIOD INFO --}}
        <h5 class="text-center mb-4">
            Periode Payroll:
            <strong>
                {{ date('F', mktime(0, 0, 0, $month, 1)) }}
                {{ $year }}
            </strong>
        </h5>

        {{-- =============================== --}}
        {{-- FILTER FORM --}}
        {{-- =============================== --}}
        <form method="GET" class="row g-2 mb-3 justify-content-center">

            {{-- Month --}}
            <div class="col-auto">
                <select name="month" class="form-select">
                    @for ($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $m == $month ? 'selected' : '' }}>
                            {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                        </option>
                    @endfor
                </select>
            </div>

            {{-- Year --}}
            <div class="col-auto">
                <select name="year" class="form-select">
                    @for ($y = now()->year - 2; $y <= now()->year + 1; $y++)
                        <option value="{{ $y }}" {{ $y == $year ? 'selected' : '' }}>
                            {{ $y }}
                        </option>
                    @endfor
                </select>
            </div>

            {{-- Button --}}
            <div class="col-auto">
                <button class="btn btn-primary">
                    <i class="fa fa-filter"></i> Filter Payroll
                </button>
            </div>

        </form>


        {{-- GENERATE PAYROLL BUTTON --}}
        <div class="text-center mb-4">

            <form method="POST" action="{{ route('payroll.generate') }}">
                @csrf

                <input type="hidden" name="month" value="{{ $month }}">
                <input type="hidden" name="year" value="{{ $year }}">

                <button class="btn btn-success px-4" {{ $alreadyGenerated ? 'disabled' : '' }}
                    onclick="return confirm('Generate payroll bulan ini?')">

                    <i class="fa fa-calculator"></i>

                    {{ $alreadyGenerated ? 'Payroll Sudah Digenerate' : 'Generate Payroll' }}

                </button>
            </form>

            @if ($alreadyGenerated)
                <p class="text-muted mt-2">
                    Payroll periode ini sudah tersedia dan tidak bisa digenerate ulang.
                </p>
            @endif

        </div>






        {{-- =============================== --}}
        {{-- TABLE --}}
        {{-- =============================== --}}
        <div class="table-responsive">
            <table class="table table-bordered table-striped w-auto mx-auto align-middle">

                <thead class="table-secondary text-center">
                    <tr>
                        <th>Employee</th>
                        <th>Position</th>
                        <th>Basic Salary</th>
                        <th>Earned Salary</th>
                        <th>Overtime Pay</th>
                        <th>Tax PPH21</th>
                        <th>Total Salary</th>
                    </tr>
                </thead>

                <tbody>

                    {{-- LOOP DATA --}}
                    @forelse ($payrolls as $row)
                        <tr>
                            <td>
                                <strong>{{ $row->fullname }}</strong><br>
                                <small class="text-muted">{{ $row->employee_id }}</small>
                            </td>

                            <td>{{ $row->role }}</td>

                            <td class="text-center">
                                Rp {{ number_format($row->basic_salary, 0, ',', '.') }}
                            </td>

                            <td class="text-center">
                                Rp {{ number_format($row->earned_salary, 0, ',', '.') }}
                            </td>

                            <td class="text-center">
                                Rp {{ number_format($row->overtime_pay, 0, ',', '.') }}
                            </td>

                            <td class="text-center text-danger">
                                Rp {{ number_format($row->tax_deduction, 0, ',', '.') }}
                            </td>

                            <td class="text-center fw-bold text-success">
                                Rp {{ number_format($row->net_salary, 0, ',', '.') }}
                            </td>
                        </tr>

                        {{-- EMPTY STATE --}}
                    @empty

                        <tr>
                            <td colspan="7" class="text-center text-danger fw-bold py-4">
                                <i class="fa fa-folder-open fa-lg mb-2"></i><br>
                                Data payroll untuk periode ini belum tersedia.<br>
                                Silakan klik <b>Close Payroll Month</b> terlebih dahulu.
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>
        </div>

        <div class="d-flex justify-content-center mt-4 mb-4">
            <form action="{{ route('overtime.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-danger px-4">
                    <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                </button>
            </form>
        </div>
    </div>
@endsection
