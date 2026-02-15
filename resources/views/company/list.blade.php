    @extends('layouts.app')

    @section('content')
        <link rel="stylesheet" href="{{ asset('css/company_view.css') }}">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.j"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

        <div class="container mt-1">

            <h2 class="mb-4">🏢 Company List</h2>



            @foreach ($companies as $company)
                {{-- INFO SECTION --}}
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

                            <p class="mission-text"><strong>Mission:</strong><br>
                                {!! nl2br(e($company->mission)) !!}
                            </p>

                            <p><strong>Established:</strong>
                                {{ $company->established_at ? $company->established_at->format('d M Y') : '-' }}
                            </p>
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
                                        style="width: {{ $company->stock_growth }}%"
                                        aria-valuenow="{{ $company->stock_growth }}" aria-valuemin="0" aria-valuemax="100">
                                        {{ $company->stock_growth }}%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    @endsection
