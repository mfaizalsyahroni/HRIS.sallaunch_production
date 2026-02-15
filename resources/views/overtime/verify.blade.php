@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<div class="container d-flex flex-column align-items-center justify-content-start min-vh-100 pt-5"
    style="background: url('{{ asset('img/part/payroll_salary.png') }}') no-repeat center; background-size: 600px;">


    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show w-100" style="max-width: 400px;" role="alert">
            <i class="bi bi-check-circle-fill me-1"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif


    <div class="card shadow bg-white bg-opacity-75 mb-3" style="width: 400px; margin-top: 50px;">
        <div class="card-body p-4 rounded">


            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif


            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <h3 class="text-center mb-4">Form Overtime</h3>

            <form action="{{ route('overtime.verifyWorker') }}" method="POST" autocomplete="off">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold">Employee ID</label>
                    <input type="text" name="employee_id" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Submit</button>
            </form>
        </div>
    </div>

    {{-- Logout Button --}}
    <form action="{{ route('overtime.logout') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-outline-danger w-100 fw-bold">
            <i class="bi bi-box-arrow-right me-2"></i>
            Logout
        </button>
    </form>
</div>
@endsection