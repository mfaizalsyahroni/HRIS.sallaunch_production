@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/overtime_design.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-S...HASH..." crossorigin="anonymous" referrerpolicy="no-referrer" />


    @if (session('verified_worker'))

        {{-- HEADER HRIS --}}
        <div class="hris-header">
            @if ($worker)
                <h3>Overtime</h3>
                <p>{{ $worker->fullname }} (ID: {{ $worker->employee_id }})</p>
            @else
                <h3>Overtime</h3>
                <p>Employee Overtime Records</p>
            @endif
        </div>

        <div class="container attendance-container mt-4">

            {{-- SUCCESS MESSAGE --}}
            @if (session('success'))
                <p style="color: green; text-align:center;">
                    {{ session('success') }}
                </p>
            @endif

            {{-- ERROR MESSAGE --}}
            @if ($errors->any())
                <div style="text-align:center;">
                    @foreach ($errors->all() as $error)
                        <p style="color:red;">{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            {{-- TABLE --}}
            <table class="visual" style="width:100%;">
                <thead>
                    <tr style="background-color:#4c8eaf; color:#fff;">
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
                    @forelse ($overtimes as $overtime)
                        <tr>
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
                            <td colspan="9">No overtime records found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- ACTION BUTTON --}}
            <div style="display:flex; justify-content:center; gap:16px; margin-top:24px;">
                @php
                    $currentOvertime = $overtimes->whereNull('end_time')->first();
                @endphp

                @if (!$currentOvertime)
                    <form action="{{ route('overtime.start') }}" method="POST">
                        @csrf
                        <button type="submit">Start Overtime</button>
                    </form>
                @else
                    <form action="{{ route('overtime.finish') }}" method="POST">
                        @csrf
                        <button type="submit">Finish Overtime</button>
                    </form>
                @endif
            </div>

            {{-- LOGOUT --}}
            <div class="d-flex justify-content-center mt-4">
                <form action="{{ route('overtime.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger px-4">
                        <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                    </button>
                </form>
            </div>

        </div>

    @endif
@endsection
