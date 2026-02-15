@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <div class="container">
        <h3 class="mb-4">🎓 Sertifikasi Saya</h3>

        @if ($certifications->isEmpty())
            <div class="alert alert-info">
                Belum ada sertifikasi yang dinilai.
            </div>
        @else
            <table class="table table-bordered align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Module</th>
                        <th>Nilai</th>
                        <th>Status</th>
                        <th>Catatan</th>
                        <th>Reviewer</th>
                        <th>Tanggal</th>
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
                                {{ $cert->reviewer->fullname ?? 'System' }}
                            </td>

                            <td>
                                {{ $cert->created_at->format('d M Y') }}
                            </td>

                            <td class="text-center">
                                @if ($cert->status === 'passed')
                                    <a href="{{ route('certification.download', $cert->id) }}"
                                        class="btn btn-sm btn-success">
                                        <i class="bi bi-download"></i> PDF
                                    </a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
