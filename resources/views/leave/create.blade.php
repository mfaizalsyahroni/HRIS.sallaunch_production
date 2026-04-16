@extends('layouts.ot')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/feature_leavess.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-S...HASH..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

    <div class="d-flex flex-column justify-content-center align-items-center min-vh-100 min-vw-100 pt-5"
        style="
        background-image: url('{{ asset('img/part/cuti.jpg') }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        margin: 0;          
        padding: 0;         
        width: 100vw;       
        min-height: 100vh;  
    ">

        <div class="wrapper p-4 bg-white bg-opacity-75 rounded shadow" style="min-width: 300px;">

            <div class=" text-center fw-bold">
                Employee's Leave Request
            </div>

            @if (session('message1'))
                <div class="notif-error" id="notification">
                    {{ session('message1') }}
                </div>
                <script>
                    setTimeout(() => document.getElementById('notification')?.remove(), 8000);
                </script>
            @endif

            <form action="{{ route('leave.verify') }}" method="POST" id="leaveForm">
                @csrf
                <div class="form-group fw-bold">
                    <label for="fullname">Name</label>
                    <input type="text" name="fullname" value="{{ old('fullname') }}" required class="input-login"
                        autocomplete="off">
                </div>
                <div class="form-group fw-bold">
                    <label for="employee_id">Employee ID</label>
                    <input type="text" name="employee_id" value="{{ old('employee_id') }}" required class="input-login"
                        autocomplete="off">
                </div>
                <div class="form-group fw-bold">
                    <label for="role">Role</label>
                    <input type="text" name="role" value="{{ old('role') }}" required class="input-login"
                        autocomplete="off">
                </div>
                <div class="form-group fw-bold">
                    <label for="password">Password</label>
                    <input type="password" name="password" required class="input-login" autocomplete="off">
                </div>
                <button type="submit" class="button-login">Submit</button>
            </form>
        </div>
        <div class="d-flex justify-content-center align-items-center py-4">
            <form action="{{ route('leave.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-danger px-4 fw-bold">
                    <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                </button>
            </form>
        </div>
    </div>
@endsection