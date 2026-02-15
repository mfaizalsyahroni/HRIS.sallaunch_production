@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-S...HASH..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <div class="container-fluid d-flex justify-content-center align-items-center min-vh-100 w-100 py-2"
        style="background: url('{{ asset('img/part/idea.jpg') }}') no-repeat center; background-size: 600px;">

        <div class="card shadow bg-white bg-opacity-75 rounded-4" style="width: 400px;">
            <div class="card-body p-4">

                <h3 class="text-center mb-4 fw-bold">Form IDEA</h3>

                <form action="{{ route('idea.verify.worker') }}" method="POST" autocomplete="off">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Employee ID</label>
                        <input type="text" name="employee_id" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password</label>
                        <input type="password" name="password" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Submit
                    </button>
                </form>

            </div>
        </div>
    </div>

    {{-- Logout di bawah container --}}
    <div class="text-center" style="margin-top:-50px;">
        <form action="{{ route('idea.logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-danger px-4 fw-bold">
                <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
            </button>
        </form>
    </div>
@endsection
