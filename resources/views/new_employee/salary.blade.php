@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <div class="container d-flex justify-content-center align-items-start min-vh-100">
        <div class="card shadow-lg border-0 rounded-4 my-4" style="max-width: 480px; width: 100%;">
            <div class="card-body p-4">

                <h4 class="text-center fw-bold mb-1">Set Salary Grade</h4>
                <p class="text-center text-muted mb-4" style="font-size: 0.9rem;">
                    For: <strong>{{ $worker->fullname }}</strong> (ID: {{ $worker->employee_id }})
                </p>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('new_employee.salary.store', $worker->employee_id) }}"
                    onsubmit="document.getElementById('basic_salary_value').value = 
                    document.getElementById('basic_salary').value.replace(/\D/g, '')">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Position</label>
                        <input type="text" name="position" class="form-control" value="{{ $worker->role }}"
                            placeholder="e.g. Trader" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Grade</label>
                        <select name="grade_name" class="form-select" required>
                            <option value="">-- Select --</option>
                            <option value="Junior">Junior</option>
                            <option value="Middle">Middle</option>
                            <option value="Senior">Senior</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Basic Salary</label>

                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" id="basic_salary" class="form-control" placeholder="4.000.000"
                                inputmode="numeric">
                        </div>

                        <!-- value asli untuk dikirim ke Laravel -->
                        <input type="hidden" name="basic_salary" id="basic_salary_value">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Employment Type</label>
                        <select name="employment_type" class="form-select" required>
                            <option value="">-- Select --</option>
                            <option value="permanent">Permanent</option>
                            <option value="contract">Contract</option>
                            <option value="intern">Intern</option>
                            <option value="probation">Probation</option>
                            <option value="freelance">Freelance</option>
                        </select>
                    </div>

                    <div class="mb-4 form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" checked>
                        <label class="form-check-label">Active</label>
                    </div>

                    <button type="submit" class="btn btn-success w-100 rounded-3">
                        Save
                    </button>
                </form>

            </div>
        </div>
    </div>

    <script>
        const display = document.getElementById('basic_salary');
        const hidden = document.getElementById('basic_salary_value');

        display.addEventListener('input', function() {
            let value = this.value.replace(/\D/g, '');
            hidden.value = value;
            this.value = new Intl.NumberFormat('id-ID').format(value);
        });
    </script>

@endsection
