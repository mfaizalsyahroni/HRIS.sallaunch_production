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
        <p><strong>Leave Type:</strong> {{ $leave->leave_types }}</p>
        <p><strong>Start Date:</strong> {{ $leave->start_date }}</p> {{-- Akan format d-m-Y otomatis --}}
        <p><strong>End Date:</strong> {{ $leave->end_date }}</p> {{-- Akan format d-m-Y otomatis --}}
        <p><strong>Reason:</strong> {{ $leave->leave_reason }}</p>
        <p><strong>Status:</strong> {{ ucfirst($leave->status) }}</p>
        

        <div class="back">
            <button onclick="window.location.href='{{ route('leave.clearSession') }}'">
                <img src="{{ asset('img/part/back.jpeg') }}" alt="back" width="50px">
            </button>
        </div>


    </div>
</div>
@endsection