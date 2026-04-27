@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/lp_log.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


    <div class="container-fluid">

        <div class="wrapper">
            <div class="subtitle">
                <h5>Login</h5>
                <h6>Learning Plan</h6>
            </div>

            <form action="{{ route('learningplan.verifyWorker') }}" method="POST" enctype="multipart/form-data"
                autocomplete="off">
                @csrf

                <div class="form-group">
                    <label for="employee_id">Employee ID</label>
                    <input type="text" name="employee_id" id="employee_id" required class="input-login"
                        placeholder="Input your unique ID...">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required class="input-login"
                        placeholder="Input your secret password...">
                </div>

                <button type="submit" class="button-login">Submit</button>
            </form>
        </div>

        <div class="mt-4">
            <form action="{{ route('learningplan.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger px-4 py-2 fw-bold shadow-sm">
                    <i class="fa-solid fa-right-from-bracket me-2"></i>Logout
                </button>
            </form>
        </div>

    </div>
@endsection
