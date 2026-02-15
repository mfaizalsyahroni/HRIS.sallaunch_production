@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-S...HASH..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <div class="container py-5">

        <!-- Header -->
        <div class="text-center mb-5">
            <h2 class="fw-bold">📘 Staff Learning Plan Modules</h2>
            <p class="text-muted">Complete modules and upload feedback video (Min 3 menit)</p>
        </div>

        <!-- Progress -->
        <div class="d-flex align-items-center mb-4">
            <!-- Circle Progress with Trophy -->
            <div class="position-relative me-3" style="width: 120px; height: 120px;">
                <svg class="position-absolute" width="120" height="120">
                    <circle cx="60" cy="60" r="50" stroke="#eee" stroke-width="10" fill="transparent" />
                    <circle cx="60" cy="60" r="50" stroke="green" stroke-width="10" fill="transparent"
                        stroke-dasharray="314" stroke-dashoffset="{{ 314 - (314 * $progress) / 100 }}"
                        style="transition: stroke-dashoffset 0.5s; transform: rotate(-90deg); transform-origin: 50% 50%;">
                    </circle>
                </svg>

                <!-- Trophy Icon + Progress Text -->
                <div class="position-relative text-center" style="width: 100%; height: 100%;">
                    <div class="fs-1">🏆</div>
                    <div class="fw-bold">{{ $progress }}%</div>
                </div>
            </div>

            <!-- Title and Info -->
            <div class="ms-2">
                <h5 class="fw-bold mb-2">
                    <i class="bi bi-trophy"></i> Kemajuan Anda
                </h5>
                <p class="mb-0">Anda telah menyelesaikan {{ $progress }}% dari modul belajar.</p>
            </div>
        </div>

        <!-- Modules -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach ($modules as $module)
                <div class="col">
                    <div class="card shadow-sm border-0 rounded-4 overflow-hidden h-100 d-flex flex-column">

                        <!-- Video -->
                        <div class="ratio ratio-16x9">
                            <iframe src="https://www.youtube.com/embed/{{ $module->youtube_id }}" allowfullscreen></iframe>
                        </div>

                        <!-- Body -->
                        <div class="card-body d-flex flex-column">
                            <span class="badge bg-secondary mb-2">{{ $module->category }}</span>
                            <h5 class="fw-bold">{{ $module->module_name }}</h5>
                            <p class="text-muted small">{{ $module->description }}</p>
                            <p class="mb-2">⏱ Duration: {{ $module->duration }}</p>

                            @if ($module->completed)
                                <span class="badge bg-success mt-auto">✅ Completed (+20%)</span>
                            @else
                                <form action="{{ route('learningplan.uploadFeedback') }}" method="POST"
                                    enctype="multipart/form-data" class="mt-auto">
                                    @csrf
                                    <input type="hidden" name="module_id" value="{{ $module->id }}">
                                    <label class="fw-bold small">Upload Feedback Video (Min 3 menit)</label>
                                    <input type="file" name="feedback_video" accept="video/*" class="form-control mt-2"
                                        required>
                                    <button class="btn btn-success w-100 mt-3 rounded-pill">Submit Feedback</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="d-flex justify-content-center mt-4">
            <form action="{{ route('overtime.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-danger px-4">
                    <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                </button>
            </form>
        </div>
    </div>
@endsection
