@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <div class="container py-4">
        <div class="row mb-4">
            <h4>👨‍💻 ADMIN IT HRIS collage</h4>
            <div class="col-md-6">
                <div class="card bg-primary text-white shadow-sm">
                    <div class="card-body">
                        <h5>Total Certified</h5>
                        <h2>{{ $totalCertified }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-success text-white shadow-sm">
                    <div class="card-body">
                        <h5>Total Passed</h5>
                        <h2>{{ $totalPassed }}</h2>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel Penilaian --}}
        <h4 class="mb-3">Pending Reviews</h4>
        @if ($pendingProgress->isEmpty())
            <div class="alert alert-info">Semua tugas sudah dinilai.</div>
        @else
            <div class="card shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Employee</th>
                                <th>Module</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pendingProgress as $progress)
                                <tr>
                                    <td>{{ $progress->worker->fullname }}</td>
                                    <td>{{ $progress->module->module_name }}</td>
                                    <td>
                                        {{-- Form Penilaian --}}
                                        <form method="POST" action="{{ route('certification.store') }}"
                                            class="d-flex gap-2">
                                            @csrf
                                            <input type="hidden" name="learning_progress_id" value="{{ $progress->id }}">
                                            <select name="score" class="form-select form-select-sm" required>
                                                <option value="A">A</option>
                                                <option value="B">B</option>
                                                <option value="C">C</option>
                                            </select>
                                            <button class="btn btn-sm btn-success">Submit</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
@endsection
