@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-S...HASH..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>



    <div class="container d-flex justify-content-center align-items-start pt-5 min-vh-100">
        <div class="card shadow-lg border-0 rounded-4" style="max-width: 420px; width: 100%;">
            <div class="card-body p-4">

                <h4 class="text-center fw-bold mb-1">Add New Worker</h4>
                <p class="text-center text-muted mb-4" style="font-size: 0.9rem;">
                    Register new employee into the system
                </p>

                <form action="{{ route('workers.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Employee ID</label>
                        <input type="text" name="employee_id" class="form-control" placeholder="e.g. 12345" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Fullname</label>
                        <input type="text" name="fullname" class="form-control" placeholder="example : John Doe"
                            required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Role</label>
                        <input type="text" name="role" class="form-control" placeholder="example : IT Support"
                            required>
                    </div>

                    <div class="mb-3">
                        <Label class="form-label fw-semibold">Employment Type</Label>
                        <select name="employment_type" class="form-select" required>
                            <option value="">-- Select Type --</option>
                            <option value="permanent">Permanent</option>
                            <option value="contract">Contract</option>
                            <option value="intern">Intern</option>
                            <option value="probation">Probation</option>
                            <option value="freelance">Freelance</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Password</label>
                        <input type="password" name="password" class="form-control" required>
                        <small class="text-muted">Minimum 2 characters</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                        <small class="text-muted">Re-enter the same password to confirm</small>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 rounded-3">
                        Add Worker
                    </button>
                </form>

            </div>
        </div>
    </div>
    <div class="d-flex justify-content-center mt-4">
        <form action="{{ route('view.allemployee') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-primary px-4">
                <i class="bi bi-people me-2"></i> View All Employees
            </button>
        </form>
    </div>
    <div class="d-flex justify-content-center mt-4">
        <form action="{{ route('new_employee.logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-outline-danger px-4">
                <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
            </button>
        </form>
    </div>
@endsection
