@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/leave_show.css') }}">

<div class="container">

    {{-- CARD --}}
    <div class="wrapper">
        <div class="title">Leave Detail</div>

        <p><strong>Name:</strong> {{ $leave->fullname }}</p>
        <p><strong>Employee ID:</strong> {{ $leave->employee_id }}</p>
        <p><strong>Leave Type:</strong> {{ $leave->leave_type }}</p>
        <p><strong>Start Date:</strong> {{ $leave->start_date }}</p>
        <p><strong>End Date:</strong> {{ $leave->end_date }}</p>
        <p><strong>Total Days:</strong> {{ $leave->total_days }} Day</p>
        <p><strong>Reason:</strong> {{ $leave->leave_reason }}</p>
        <p><strong>Status:</strong> {{ ucfirst($leave->status) }}</p>

        @if ($worker)
            <p>
                <strong>Remaining Leave:</strong>
                <span class="badge fs-6 {{ $worker->leave_balance <= 3 ? 'bg-danger' : 'bg-success' }}">
                    {{ $worker->leave_balance }} / 12 Day
                </span>
            </p>
        @endif
    </div>

    {{-- BACK BUTTON DI LUAR --}}
    <div class="back-outside">
        <button onclick="window.location.href='{{ route('leave.clearSession') }}'">
            <img src="{{ asset('img/part/back.jpeg') }}" width="50px">
        </button>
    </div>

</div>
@endsection