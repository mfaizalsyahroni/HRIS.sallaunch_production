@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/payroll_log.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

    <div class="d-flex justify-content-center align-items-center min-vh-100 position-relative">

        <div class="wrapper p-4 rounded shadow" style="width: 100%; max-width: 380px;">

            <h1 class="title text-center">Payroll</h1>

            <form action="{{ route('payroll.verifyWorker') }}" method="POST" class="form-login"
                enctype="multipart/form-data" autocomplete="off">
                @csrf

                <div class="form-group">
                    <label for="employee_id">Employee ID</label>
                    <input type="text" name="employee_id" id="employee_id" required class="input-login">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required class="input-login">
                </div>

                <button type="submit" class="button-login">Submit</button>
            </form>

        </div>

        <div class="position-absolute bottom-0 mb-4">
            <form action="{{ route('payroll.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-danger px-4 fw-bold">
                    <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                </button>
            </form>
        </div>

    </div>
@endsection