@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/overtime_design.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-S...HASH..." crossorigin="anonymous" referrerpolicy="no-referrer" />

    <div class="container">
        <div class="hris-header mb-2">
            <h3>Dashboard Admin Overtime</h3>
            <p>Monitor and verify employee overtime data</p>
        </div>




        @if ($isAdmin)
            <div class="d-flex justify-content-center mb-4">
                <form method="GET" action="{{ route('overtime.admin.dashboard') }}"
                    class="d-flex align-items-center gap-2">

                    <label class="fw-semibold mb-0">Select Worker:</label>

                    <select name="worker_id" class="form-select" style="width: 260px" onchange="this.form.submit()">
                        <option value="">-- All Worker --</option>

                        @foreach ($workers as $worker)
                            <option value="{{ $worker->id }}" {{ request('worker_id') == $worker->id ? 'selected' : '' }}>
                                {{ $worker->fullname }} ({{ $worker->employee_id }})
                            </option>
                        @endforeach
                    </select>

                </form>
            </div>
        @endif


        <table class="visual">
            <thead>
                <tr style="background-color: #4c8eaf; color: #fff;">
                    <th>Employee ID</th>
                    <th>Worker</th>
                    <th>Overtime Date</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Actual (Hours & Minutes)</th>
                    <th>Total Hours</th>
                    <th>Hourly Wage</th>
                    <th>Total Payment</th>
                    <th>Status</th>
                    <th>Day Type</th>
                </tr>
            </thead>
            <tbody>
                {{-- try with forelse because, there is a message when the data is empty --}}
                @forelse ($overtimes as $overtime)
                    <tr>
                        <td>{{ $overtime->worker->employee_id }}</td>
                        <td>{{ $overtime->worker->fullname ?? '-' }}</td>
                        <td>{{ $overtime->formatted_overtime_date }}</td>
                        <td>{{ $overtime->start_time ?? '-' }}</td>
                        <td>{{ $overtime->end_time ?? '-' }}</td>
                        <td>{{ $overtime->actual_hour_minute }}</td>
                        <td>{{ number_format($overtime->total_work_hours ?? 0, 2) }}</td>
                        <td>{{ $overtime->formatted_hourly_wage }}</td>
                        <td>{{ $overtime->formatted_total_payment }}</td>
                        <td>
                            @if ($overtime->end_time)
                                <span class="badge bg-success">Finished</span>
                            @elseif ($overtime->start_time)
                                <span class="badge bg-warning text-dark">In Progress</span>
                            @else
                                <span class="badge bg-secondary">Not Started</span>
                            @endif
                        </td>
                        <td>{{ $overtime->is_weekend ? 'Weekend' : 'Weekday' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">Data kosong</td>
                    </tr>
                @endforelse
        </table>

        <div class="d-flex justify-content-center mt-4">
            <form action="{{ route('overtime.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-danger px-4">
                    <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                </button>
            </form>
        </div>

        </tbody>

    </div>
@endsection
