@extends('layouts.ot')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/company.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <div class="d-flex justify-content-center align-items-center min-vh-100"
        style="
        background-image: url('{{ asset('img/background/sal.jpg') }}');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        margin: 0;
        padding: 0;
        width: 100vw;
    ">

        <div class="wrapper p-4 bg-white bg-opacity-75 rounded shadow" style="min-width: 320px;">

            <div class="text-center fw-bold mb-3">
                <h4>Company Access</h4>
            </div>

            @if (session('error'))
                <div class="notif-login" id="notification">
                    {{ session('error') }}
                </div>
                <script>
                    setTimeout(() => document.getElementById('notification')?.remove(), 5000);
                </script>
            @endif

            <form action="{{ route('company.verifyWorker') }}" method="POST" class="form-login">
                @csrf

                <div class="form-group fw-bold">
                    <label for="employee_id">Employee ID</label>
                    <input type="text" name="employee_id" required class="input-login" autocomplete="off">
                </div>

                <div class="form-group fw-bold">
                    <label for="password">Password</label>
                    <input type="password" name="password" required class="input-login" autocomplete="off">
                </div>

                <button type="submit" class="button-login mt-2">Submit</button>
            </form>
        </div>

        <div style="position: absolute; bottom: 30px;"">
            <form action="{{ route('company.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-danger px-4 fw-bold">
                    <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                </button>
            </form>
        </div>

    </div>
@endsection