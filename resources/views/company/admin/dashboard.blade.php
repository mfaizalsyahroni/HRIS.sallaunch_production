@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/company_view.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.j"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <div class="container1 mt-1">

        <h2 class="container mb-4">🏢 Company Admin Dashboard</h2>

        {{-- Success Message --}}
        @if (session('success'))
            <div class="alert alert-success mt-2">{{ session('success') }}</div>
        @endif



        {{-- LOOP PERUSAHAAN --}}
        @foreach ($companies as $company)
            {{-- Company Information Card --}}
            <div class="col-md-12 mb-4">
                <div class="card p-4 d-flex flex-row align-items-start">

                    {{-- INFO --}}
                    <div class="flex-grow-1">
                        <h4 class="mb-3"><i class="bi bi-building-fill"></i> Company Information</h4>

                        {{-- LOGO (lebih besar) --}}
                        <div class="me-4 mb-4">
                            @if ($company->logo)
                                <img src="{{ asset('storage/' . $company->logo) }}" alt="Company Logo"
                                    style="width: 400px; 
                            height: 250px; 
                            object-fit: cover; 
                            border-radius: 8px;">
                            @else
                                <div class="d-flex align-items-center justify-content-center bg-light border rounded"
                                    style="width: 150px; height: 150px;">
                                    <span class="text-muted">No Logo</span>
                                </div>
                            @endif
                        </div>

                        <p><strong>Company Name:</strong> {{ $company->name ?? '-' }}</p>

                        <p><strong>Description:</strong><br>
                            {{ $company->description ?? '-' }}
                        </p>

                        <p><strong>Vision:</strong><br>
                            {!! nl2br(e($company->vision)) !!}
                        </p>

                        <p><strong>Mission:</strong><br>
                            {!! nl2br(e($company->mission)) !!}
                        </p>


                        <p><strong>Established:</strong>
                            {{ $company->established_at ? $company->established_at->format('d M Y') : '-' }}
                        </p>
                    </div>

                </div>
            </div>





            {{-- Card Grid --}}
            <div class="row">
                {{-- Total Employees --}}
                <div class="col-md-4">
                    <div class="card p-3">
                        <h4><i class="bi bi-people-fill"></i> Total Employees</h4>
                        <p class="h3">{{ $company->employee_count }}</p>
                        <span class="badge badge-pill bg-gradient" style="background: #ffb347;">🔥 Hot Team</span>
                    </div>
                </div>

                {{-- Total Departments --}}
                <div class="col-md-4">
                    <div class="card p-3">
                        <h4><i class="bi bi-diagram-3-fill"></i> Departments</h4>
                        <p class="h3">{{ $company->department_count }}</p>
                    </div>
                </div>

                {{-- Total Branches --}}
                <div class="col-md-4">
                    <div class="card p-3">
                        <h4><i class="bi bi-building"></i> Branches</h4>
                        <p class="h3">{{ $company->branch_count }}</p>
                        <span class="badge badge-pill bg-gradient" style="background: #6a11cb;">New Branch</span>
                    </div>
                </div>

                {{-- Total Projects --}}
                <div class="col-md-4">
                    <div class="card p-3">
                        <h4><i class="bi bi-kanban-fill"></i> Projects</h4>
                        <p class="h3">{{ $company->project_count }}</p>
                        <span class="badge badge-pill bg-gradient" style="background: #ff512f;">Key Project</span>
                    </div>
                </div>

                {{-- Stock Value --}}
                <div class="col-md-4">
                    <div class="card p-3">
                        <h4><i class="bi bi-currency-dollar"></i> Stock Value 💵</h4>
                        <p class="h3">{{ $company->stock_value_formatted }}</p>
                    </div>
                </div>


                {{-- Stock Growth --}}
                <div class="col-md-4">
                    <div class="card p-3">
                        <h4><i class="bi bi-graph-up"></i> Stock Growth</h4>
                        <p class="h3 {{ $company->stock_growth >= 0 ? 'growth-positive' : 'growth-negative' }}">
                            {{ $company->stock_growth }}%
                        </p>
                        <div class="progress mt-2">
                            <div class="progress-bar bg-success" role="progressbar"
                                style="width: {{ $company->stock_growth }}%" aria-valuenow="{{ $company->stock_growth }}"
                                aria-valuemin="0" aria-valuemax="100">
                                {{ $company->stock_growth }}%
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            {{-- Update Company Stock --}}
            <div class="card mt-4 p-4">
                <h4>Update Company Stock</h4>
                <form action="{{ route('company.updateStock') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label>Total Employees</label>
                        <input type="number" name="employee_count" class="form-control"
                            value="{{ old('employee_count', $company->employee_count ?? 0) }}">
                    </div>
                    <div class="mb-3">
                        <label>Total Departments</label>
                        <input type="number" name="department_count" class="form-control"
                            value="{{ old('department_count', $company->department_count ?? 0) }}">
                    </div>
                    <div class="mb-3">
                        <label>Total Branches</label>
                        <input type="number" name="branch_count" class="form-control"
                            value="{{ old('branch_count', $company->branch_count ?? 0) }}">
                    </div>
                    <div class="mb-3">
                        <label>Total Projects</label>
                        <input type="number" name="project_count" class="form-control"
                            value="{{ old('project_count', $company->project_count ?? 0) }}">
                    </div>
                    <div class="mb-3">
                        <label>Total Stock Value (IDR)</label>
                        <input type="number" name="stock_value" class="form-control"
                            value="{{ old('stock_value', $company->stock_value ?? 0) }}">
                    </div>
                    <div class="mb-3">
                        <label>Stock Growth (%)</label>
                        <input type="number" step="0.01" name="stock_growth" class="form-control"
                            value="{{ old('stock_growth', $company->stock_growth ?? 0.0) }}">
                    </div>
                    <button class="btn btn-primary">Save Changes</button>
                </form>
            </div>


            {{-- Edit Company Info --}}
            <div class="card mt-4 p-4">
                <h4>Edit Company Information</h4>
                <div class="mb-3">
                    <form action="{{ route('company.updateStock', $company->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <label>Company Name</label>
                        <input type="text" name="name" class="form-control mb-3" value="{{ old('name') ?? '' }}">
                        <label>Description</label>
                        <textarea name="description" class="form-control mb-3">{{ old('description') ?? '' }}</textarea>
                        <label>Vision</label>
                        <textarea name="vision" class="form-control mb-3">{{ old('vision') ?? '' }}</textarea>
                        <label>Mission</label>
                        <textarea name="mission" class="form-control mb-3">{{ old('mission') ?? '' }}</textarea>
                        <label>Date Established</label>
                        <input type="date" name="established_at" class="form-control mb-3"
                            value="{{ $company->established_at }}">
                        <label>Logo</label>
                        <input type="file" name="logo" class="form-control mb-3">
                        @if ($company->logo)
                            <p>Current Logo:</p>
                            <img src="/storage/company/{{ $company->logo }}" width="120">
                        @endif
                        <button class="btn btn-success">Save</button>
                    </form>
                </div>
            </div>

            {{-- Delete Company --}}
            <div class="card mt-3 p-3">
                <h4>Delete Company Information</h4>
                <form action="{{ route('company.destroy', $company->id) }}" method="POST"
                    onsubmit="return confirm('Delete this company?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger">Delete</button>
                </form>
            </div>
        @endforeach
    </div>
    {{-- BUTTON TAMBAH PERUSAHAAN BARU --}}
    <div class="text-center my-5">
        <form action="{{ route('company.storeInstant') }}" method="POST">
            @csrf
            <button class="btn btn-outline-primary btn-lg px-5">
                + Tambah Perusahaan Baru
            </button>
        </form>
    </div>
@endsection
