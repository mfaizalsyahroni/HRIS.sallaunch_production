@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/news.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-S...HASH..." crossorigin="anonymous" referrerpolicy="no-referrer" />


    <div class="d-flex flex-column justify-content-between align-items-center min-vh-100 min-vw-100 pt-5"
        style="background: url('{{ asset('img/background/news.jpg') }}') no-repeat center center; background-size: cover; padding: 2rem 0;">

        <!-- Wrapper login di tengah -->
        <div class="wrapper p-4 bg-white bg-opacity-75 rounded shadow" style="min-width: 300px; margin-top: 50px;">
            <h1 class="title mb-4">News Access</h1>

            @if ($errors->any())
                <div class="notif-login">{{ $errors->first('message') }}</div>
            @endif

            <form action="{{ route('news.verifyWorker') }}" method="POST" class="form-login">
                @csrf
                <div class="form-group mb-3 fw-bold">
                    <label for="employee_id">Employee ID</label>
                    <input type="text" name="employee_id" required class="input-login" autocomplete="off">
                </div>

                <div class="form-group mb-4 fw-bold">
                    <label for="password">Password</label>
                    <input type="password" name="password" required class="input-login" autocomplete="off">
                </div>

                <button type="submit" class="button-login w-100 fw-bold">Submit</button>
            </form>
        </div>

        <!-- Logout selalu di bawah -->
        <div class="d-flex justify-content-center align-items-center mb-3">
            <form action="{{ route('news.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-danger px-4 fw-bold">
                    <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                </button>
            </form>
        </div>
    </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

@endsection
