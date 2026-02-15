@extends('layouts.app')


@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-S...HASH..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


    <div class="container d-flex justify-content-center align-items-start pt-5 min-vh-100">
        <div class="card shadow-lg border-0 rounded-4" style="max-width: 1000pxpx; width: 100%;">
            <div class="card-body p-4">

                <h4 class="text-center fw-bold mb-3">Employee Details at PT SALLAUNCH PRODUCTION</h4>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped w-100 align-middle">

                        <thead class="table-secondary text-center">
                            <tr>
                                <th>Employee ID</th>
                                <th>Fullname</th>
                                <th>Role</th>
                                <th>Working Period Start</th>
                                <th>Employment Type</th>
                            </tr>
                        </thead>

                        <tbody>
                            {{-- LOOP DATA --}}
                            @forelse ($worker as $row)
                                <tr>
                                    <td>
                                        <strong>{{ $row->employee_id }}</strong>
                                    </td>
                                    <td><small class="text-muted">{{ $row->fullname }}</small></td>
                                    <td>{{ $row->role }}</td>
                                    <td>{{ \Carbon\Carbon::parse($row->working_period_start)->format('d M Y') }}</td>
                                    <td>{{ $row->employment_type }}</td>
                                </tr>

                                {{-- EMPTY STATE --}}
                            @empty

                                {{-- <tr>
                            <td colspan="7" class="text-center text-danger fw-bold py-4">
                                <i class="fa fa-folder-open fa-lg mb-2"></i><br>
                                Data payroll untuk periode ini belum tersedia.<br>
                                Silakan klik <b>Close Payroll Month</b> terlebih dahulu.
                            </td>
                        </tr> --}}
                            @endforelse

                        </tbody>

                    </table>
                </div>

            </div>
        </div>
    </div>
    <div class="d-flex justify-content-center my-3">
        <form action="{{ route('new_employee.logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-danger px-4">
                <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
            </button>
        </form>
    </div>
@endsection
