@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <div class="container py-4">
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h4>📋 Management Trainee Review Dashboard</h4>
                <p class="text-muted">Selamat datang Reviewer (ID: 111). Silakan nilai tugas masuk di bawah ini.</p>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($pendingProgress->isEmpty())
            <div class="alert alert-info">Belum ada tugas yang perlu dinilai.</div>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle bg-white border">
                    <thead class="table-dark">
                        <tr>
                            <th>Karyawan</th>
                            <th>Modul</th>
                            <th>Video Feedback</th>
                            <th>Penilaian</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pendingProgress as $progress)
                            <tr>
                                <td>
                                    <strong>{{ $progress->worker->fullname }}</strong><br>
                                    <small class="text-muted">{{ $progress->employee_id }}</small>
                                </td>
                                <td>{{ $progress->module->module_name }}</td>
                                <td>
                                    <video width="200" controls>
                                        <source src="{{ asset('storage/' . $progress->feedback_video) }}">
                                    </video>
                                </td>
                                <td>
                                    <form method="POST" action="{{ route('certification.store') }}" class="row g-2">
                                        @csrf
                                        <input type="hidden" name="learning_progress_id" value="{{ $progress->id }}">
                                        <div class="col-auto">
                                            <select name="score" class="form-select form-select-sm" required>
                                                <option value="">Nilai</option>
                                                <option value="A">A (Lulus)</option>
                                                <option value="B">B (Lulus)</option>
                                                <option value="C">C (Gagal)</option>
                                                <option value="D">D (Gagal)</option>
                                            </select>
                                        </div>
                                        <div class="col">
                                            <input type="text" name="notes" class="form-control form-select-sm"
                                                placeholder="Catatan...">
                                        </div>
                                        <div class="col-auto">
                                            <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
