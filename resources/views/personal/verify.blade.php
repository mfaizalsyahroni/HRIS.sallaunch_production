@extends('layouts.personal')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-S...HASH..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <div class="d-flex flex-column justify-content-center align-items-center min-vh-100 w-100"
        style="
            background-image: url('{{ asset('img/background/sal.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            margin: 0;
            padding: 0;
        ">

        <div class="p-4 bg-white bg-opacity-75 rounded shadow-lg mx-3"
            style="width: 100%; max-width: 400px; border: 2px solid #333;">

            <h1 class="text-center mb-4 fw-bold" style="color: #333;">Personal Info</h1>

            <form action="{{ route('personal.verifyWorker') }}" method="POST" autocomplete="off">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold">Employee ID</label>
                    <input type="text" name="employee_id" required class="form-control" placeholder="Input Unique ID...">
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Password</label>
                    <input type="password" name="password" required class="form-control" placeholder="Input Password...">
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 mt-2 fw-bold">Submit</button>
            </form>
        </div>

        <div class="d-flex justify-content-center align-item-center py-4">
            <form action="{{ route('personal.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger px-5 fw-bold shadow">
                    <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                </button>
            </form>
        </div>

    </div>
@endsection
