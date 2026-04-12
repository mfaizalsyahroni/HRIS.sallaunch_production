{{-- resources/views/leave/show.blade.php --}}
@extends('layouts.app') {{-- Asumsikan ada layout utama, sesuaikan jika perlu --}}

@section('content')
    <link rel="stylesheet" href="{{ asset('css/feature_leave.css') }}">

    <div class="container">
        <div class="wrapper">
            <div class="title">
                Leave Detail
            </div>

            {{-- Tampilkan data leave, sinkron dengan model --}}
            <p><strong>Name:</strong> {{ $leave->fullname }}</p> {{-- Asumsikan field name ada di Worker --}}
            <p><strong>Employee ID:</strong> {{ $leave->employee_id }}</p>
            <p><strong>Leave Type:</strong> {{ $leave->leave_type }}</p>
            <p><strong>Start Date:</strong> {{ $leave->start_date }}</p> {{-- Akan format d-m-Y otomatis --}}
            <p><strong>End Date:</strong> {{ $leave->end_date }}</p> {{-- Akan format d-m-Y otomatis --}}
            <p><strong>Total Days:</strong> {{ $leave->total_days }} Day</p>
            <p><strong>Reason:</strong> {{ $leave->leave_reason }}</p>
            <p><strong>Status:</strong> {{ ucfirst($leave->status) }}</p>

            {{-- Remaining Leave --}}
            @if ($worker)
                <p>
                    <strong>Remaining Leave:</strong>
                    <span class="badge fs-6 {{ $worker->leave_balance <= 3 ? 'bg-danger' : 'bg-success' }}">
                        {{ $worker->leave_balance }}
                        / 12 Day
                    </span>
                </p>
            @endif    


                <div class="back">
                    <button onclick="window.location.href='{{ route('leave.clearSession') }}'">
                        <img src="{{ asset('img/part/back.jpeg') }}" alt="back" width="50px">
                    </button>
                </div>


        </div>
    </div>
@endsection
