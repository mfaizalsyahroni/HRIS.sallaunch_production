@extends('layouts.app')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/monitor.css') }}">
    


    <div class="container cctv-wrapper">
        <h3 class="fw-bold mb-4 text-center">CCTV Monitoring Dashboard</h3>

        <div class="row g-4">

            @foreach ($cctvs as $cctv)
                <div class="col-lg-6 col-md-6">
                    <div class="cctv-card">

                        <div class="text-center">

                            {{-- Name --}}
                            <p class="cctv-name">{{ $cctv->name }}</p>

                            {{-- Location --}}
                            <p class="location-label">
                                <i class="bi bi-geo-alt-fill text-primary"></i> {{ $cctv->location }}
                            </p>

                            {{-- Type --}}
                            @if ($cctv->type)
                                <span class="badge-type">
                                    <i class="bi bi-camera-video"></i> {{ $cctv->type }}
                                </span>
                            @endif

                            {{-- Status --}}
                            <p class="mt-2">
                                <span class="status-led {{ $cctv->online ? 'online' : 'offline' }}"></span>
                                <strong>{{ $cctv->online ? 'Online' : 'Offline' }}</strong>
                            </p>

                            {{-- Notes --}}
                            @if ($cctv->notes)
                                <p class="text-muted small">
                                    <i class="bi bi-info-circle"></i> {{ $cctv->notes }}
                                </p>
                            @endif

                            {{-- MONITOR --}}
                            @if ($cctv->source && $cctv->online)
                                <div class="monitor-wrap">
                                    <div class="monitor">
                                        <div class="monitor-screen">
                                            <iframe src="{{ $cctv->source }}" width="100%" height="100%" allowfullscreen
                                                frameborder="0"></iframe>
                                        </div>
                                    </div>

                                    <div class="monitor-stand"></div>
                                    <div class="monitor-base"></div>
                                </div>
                            @else
                                <div class="alert alert-warning mt-3">
                                    <i class="bi bi-exclamation-triangle"></i> Stream Unavailable
                                </div>
                            @endif

                        </div>

                    </div>
                </div>
            @endforeach

        </div>
    </div>
@endsection
