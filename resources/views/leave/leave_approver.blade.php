@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/look.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-S...HASH..." crossorigin="anonymous" referrerpolicy="no-referrer" />

    <div class=" min-vh-100 w-100 d-flex flex-column align-items-center"
        style="background: url('{{ asset('img/part/cuti.png') }}') no-repeat center center; 
            background-size: cover;">



        <div class="container p-0">
            <h1 class="text-center">Admin Panel: Leave Approval</h1>
            <h3 class="text-center">Leave Requests for This Month</h3>
        </div>

        @if (session('success'))
            <p class="alert-success">{{ session('success') }}</p>
        @endif

        @if (session('rejected'))
            <p class="alert-rejected">{{ session('rejected') }}</p>
        @endif

        <table class="visual">
            <tr>
                <th>Employee ID</th>
                <th>Name</th>
                <th>Leave Types</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Leave Reason</th>
                <th>Status</th>
                <th>Actions</th>
                <th>Detail</th>
            </tr>

            @foreach ($leaves as $leave)
                <tr>
                    <td>{{ $leave->employee_id }}</td>
                    <td>{{ $leave->fullname }}</td>
                    <td>{{ $leave->leave_types }}</td>
                    <td>{{ $leave->start_date }}</td>
                    <td>{{ $leave->end_date }}</td>
                    <td>{{ $leave->leave_reason }}</td>
                    <td>{{ ucfirst($leave->status) }}</td>

                    <td>
                        @if ($leave->status === 'pending')
                            <form action="{{ route('leave.approve', $leave->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn-approve">Approve</button>
                            </form>

                            <form action="{{ route('leave.reject', $leave->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn-reject">Reject</button>
                            </form>
                        @else
                            -
                        @endif
                    </td>

                    <td>
                        <a href="{{ route('leave.show', $leave->id) }}">
                            <button class="btn-detail">Detail</button>
                        </a>
                    </td>
                </tr>
            @endforeach
        </table>
        <div class="d-flex justify-content-center align-items-center mt-3">
            <form action="{{ route('leave.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-danger px-4 fw-bold">
                    <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                </button>
            </form>
        </div>

    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@endsection
