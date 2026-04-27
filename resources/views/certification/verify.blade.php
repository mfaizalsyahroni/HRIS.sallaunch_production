@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-S...HASH..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <div class="container-fluid d-flex flex-column justify-content-center align-items-center min-vh-100"
        style="background: url('{{ asset('img/home/sertifikat.png') }}')  no-repeat center; background-size: 500px; position: relative;">

        <div style="flex: 1;"></div> {{-- top spacer --}}

        <div class="card shadow bg-white bg-opacity-75" style="width: 400px;">
            <div class="card-body p-4 rounded">

                <h3 class="text-center mb-4">Login Ceritification</h3>

                <form action="{{ route('certification.verifyWorker') }}" method="POST" enctype="multipart/form-data"
                    autocomplete="off">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Employee ID</label>
                        <input type="text" name="employee_id" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        Submit
                    </button>
                </form>
            </div>
        </div>

        <div style="flex: 1;"></div> {{-- bottom spacer --}}

        <div class="mb-2">
            <form action="{{ route('certification.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger px-4 fw-bold">
                    <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                </button>
            </form>
        </div>
    </div>
@endsection
