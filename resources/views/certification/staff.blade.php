@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-S...HASH..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <div class="container py-4">
        <h3 class="mb-4">🎓 My Certifications</h3>

        {{-- Alert Deadline dari Admin --}}
        @if (session('admin_alert'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="bi bi-megaphone-fill me-2"></i>
                {{ session('admin_alert') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if ($certifications->isEmpty())
            <div class="alert alert-info">
                No certifications have been graded yet.
            </div>
        @else
            <div class="table-responsive rounded-4 overflow-hidden border">
                <table class="table table-bordered align-middle mb-0">
                    <thead class="table-primary">
                        <tr>
                            <th>Module</th>
                            <th>Grade</th>
                            <th>Status</th>
                            <th>Notes</th>
                            <th>Reviewer</th>
                            <th>Date</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($certifications as $cert)
                            <tr>
                                <td>
                                    <strong>{{ $cert->module->module_name }}</strong><br>
                                    <small>{{ $cert->module->category }}</small>
                                </td>

                                <td class="text-center fw-bold">
                                    {{ $cert->score }}
                                </td>

                                <td class="text-center">
                                    @if ($cert->status === 'passed')
                                        <span class="badge bg-success">PASSED</span>
                                    @else
                                        <span class="badge bg-danger">FAILED</span>
                                    @endif
                                </td>

                                <td>
                                    {{ $cert->notes ?? '-' }}
                                </td>

                                <td>
                                    {{ $cert->reviewer->fullname ?? 'Management Trainee' }}
                                </td>

                                <td>
                                    {{ $cert->created_at->format('d M Y') }}
                                </td>

                                <td class="text-center">
                                    @if ($cert->status === 'passed')
                                        <a href="{{ route('certification.download', $cert->id) }}"
                                            class="btn btn-sm btn-danger">
                                            <i class="bi bi-file-pdf-fill"></i> PDF
                                        </a>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
        <div class="d-flex justify-content-center align-item-center py-4">
            <form action="{{ route('certification.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-danger px-4">
                    <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                </button>
            </form>

        </div>
    </div>
@endsection
