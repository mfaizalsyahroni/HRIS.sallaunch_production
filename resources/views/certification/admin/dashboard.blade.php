@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <div class="container py-4">
        <div class="row mb-4">
            <div class="fw-bold mb-3" style="font-size: 24px">
                👨‍💻 ADMIN IT HRIS collage
            </div>
            <div class="col-md-6">
                <div class="card bg-light bg-gradient text-secondary shadow-sm">
                    <div class="card-body fw-bold">
                        <div style="font-size: 16px">Total Certified</div>
                        <div style="font-size: 14px">{{ $totalCertified }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card bg-light bg-gradient text-secondary shadow-sm">
                    <div class="card-body fw-bold">
                        <div style="font-size: 16px">Total Passed</div>
                        <div style="font-size: 14px">{{ $totalPassed }}</div>
                    </div>
                </div>
            </div>
        </div>


        {{-- Broadcast Alert ke Semua Staff --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h6 class="mb-3"><i class="bi bi-megaphone-fill me-2"></i>Send Deadline Reminder</h6>
                <form method="POST" action="{{ route('admin.broadcast') }}" class="d-flex gap-2">
                    @csrf
                    <input type="text" name="message" class="form-control"
                        placeholder="Example: Complete the module before Apr 30, 2026!" required>
                    <button class="btn bg-secondary bg-gradient btn-outline-secondary text-white text-nowrap">
                        <i class="bi bi-send-fill me-2"></i>Send Alert
                    </button>
                </form>
            </div>
        </div>

        <select name="certificate_title" class="form-select">
            @foreach (App\Models\LearningModule::CERTIFICATE_TITLES as $key => $label)
                <option value="{{ $key }}">{{ $label }}</option>
            @endforeach
        </select>

        <div class="fw-bold py-3" style="font-size: 24px">
            ALL Emmployee Detail
        
        @if ($pendingProgress->isEmpty())
            <div class="alert alert-info">All tasks are reviewed every day.</div>
        @else
            <div class="card shadow-sm">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-secondary tabale-striped">
                            <tr>
                                <th>Employee ID</th>
                                <th>Fullname</th>
                                <th>Module</th>
                                <th>Feedback</th>
                                <th>Progress</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pendingProgress as $progress)
                                <tr>
                                    <td>{{ $progress->worker?->employee_id }}</td>
                                    <td>{{ $progress->module->$worker->fullname }}</td>
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
    </div>

    <div class="d-flex justify-content-center align-item-center py-3 px-4">
        <form action="{{ route('certification.logout') }}" method="POST">
            @csrf
            <button class="btn btn-outline-danger fw-bold px-4">
                <i class="bi bi-box-arrow-right me-2"></i>Logout
            </button>
        </form>

    </div>
@endsection
