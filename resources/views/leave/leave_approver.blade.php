@extends('layouts.leave')

@section('title', 'Admin Panel: Leave Approval')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- Background Layer --}}
    <div
        style="
        position: fixed;
        top: 0; left: 0;
        width: 100vw; height: 100vh;
        background: url('{{ asset('img/part/cuti.png') }}') no-repeat center center;
        background-size: cover;
        z-index: -1;
    ">
    </div>

    {{-- Content Wrapper --}}
    <div class="w-100 d-flex flex-column align-items-center" style="min-height: 100vh; padding: 40px 0;">

        {{-- Header --}}
        <div class="container text-center mb-4">
            <h1 class="text-white py-2 rounded shadow">Admin Panel: Leave Approval</h1>
            <h3 class="text-warning fw-bold" style="text-shadow: 0 0 10px #ffc107, 0 0 20px #ff9800;">
                Leave Requests for This Month
            </h3>
        </div>

        {{-- Alerts --}}
        <div class="container text-center mb-3">
            @if (session('success'))
                <div class="alert alert-success d-inline-block">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('rejected'))
                <div class="alert alert-danger d-inline-block">
                    {{ session('rejected') }}
                </div>
            @endif
        </div>

        {{-- Table --}}
        <div class="container table-responsive">
            <table class="table table-striped table-hover table-bordered align-middle text-center">
                <thead>
                    <tr>
                        <th class="bg-warning text-light">Employee ID</th>
                        <th class="bg-warning text-light">Name</th>
                        <th class="bg-warning text-light">Leave Types</th>
                        <th class="bg-warning text-light">Start Date</th>
                        <th class="bg-warning text-light">End Date</th>
                        <th class="bg-warning text-light">Leave Reason</th>
                        <th class="bg-warning text-light">Status</th>
                        <th class="bg-warning text-light">Actions</th>
                        <th class="bg-warning text-light">Detail</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($leaves as $leave)
                        <tr>
                            <td>{{ $leave->employee_id }}</td>
                            <td>{{ $leave->fullname }}</td>
                            <td>{{ $leave->leave_type }}</td>
                            <td>{{ $leave->start_date }}</td>
                            <td>{{ $leave->end_date }}</td>
                            <td>{{ $leave->leave_reason }}</td>
                            <td>
                                @if ($leave->status === 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif ($leave->status === 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @elseif ($leave->status === 'rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                @endif
                            </td>
                            <td>
                                @if ($leave->status === 'pending')
                                    <form action="{{ route('leave.approve', $leave->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                    </form>
                                    <form action="{{ route('leave.reject', $leave->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                                    </form>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('leave.show', $leave->id) }}" class="btn btn-primary btn-sm">Detail</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Logout --}}
        <div class="mt-4 mb-4">
            <form action="{{ route('leave.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-danger px-4 fw-bold">
                    <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                </button>
            </form>
        </div>

    </div>

@endsection
