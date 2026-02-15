@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <div class="container-fluid d-flex justify-content-center align-items-center min-vh-100"
        style="background: url('{{ asset('img/home/achievement.jpg') }}') no-repeat center; background-size: 600px;">

        <div class="card shadow bg-white bg-opacity-75" style="width: 400px;">
            <div class="card-body p-4 rounded">

                <h3 class="text-center mb-4">Login Achievement </h3>

                <form action="{{ route('achievement.verifyWorker') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
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
    </div>
@endsection
