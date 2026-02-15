@extends('layouts.app')

@php
    use Illuminate\Support\Str;
@endphp

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/monitor.css') }}">

    <div class="container mt-5">
        <h3 class="fw-bold mb-4 text-center">CCTV Admin Dashboard</h3>

        {{-- Button Add CCTV --}}
        <div class="mb-3 text-end">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bi bi-plus-lg"></i> Add CCTV
            </button>
        </div>

        {{-- Card List CCTV --}}
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">List of CCTV</h5>
            </div>
            <div class="card-body p-0">
                {{-- Flash Message --}}
                @if (session('message'))
                    <div class="alert alert-success m-3">{{ session('message') }}</div>
                @endif

                {{-- CCTV Table --}}
                <div class="table-responsive">
                    <table class="table table-bordered mb-0 table-hover align-middle text-center">
                        <thead class="table-light">
                            <tr>
                                <th>No.</th>
                                <th>Name</th>
                                <th>Location</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Source URL</th>
                                <th>Notes</th>
                                <th style="width: 200px">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cctvs as $index => $cctv)
                                <tr>
                                    <td>{{ $cctvs->firstItem() + $index }}</td>

                                    <td>{{ $cctv->name }}</td>

                                    <td>{{ $cctv->location }}</td>

                                    {{-- Type Badge --}}
                                    <td>
                                        <span
                                            class="badge 
                                    @if ($cctv->type == 'IP Camera') bg-primary 
                                    @elseif($cctv->type == 'Analog Camera') bg-secondary
                                    @elseif($cctv->type == 'Dome Camera') bg-info
                                    @elseif($cctv->type == 'Bullet Camera') bg-success
                                    @elseif($cctv->type == 'PTZ Camera') bg-warning text-dark
                                    @elseif($cctv->type == 'Wireless Camera') bg-dark
                                    @elseif($cctv->type == 'Thermal Camera') bg-danger
                                    @else bg-light text-dark @endif
                                    badge-type">
                                            {{ $cctv->type ?? 'N/A' }}
                                        </span>
                                    </td>

                                    {{-- Status --}}
                                    <td>
                                        @if ($cctv->online)
                                            <span class="badge bg-success badge-type">Online</span>
                                        @else
                                            <span class="badge bg-danger badge-type">Offline</span>
                                        @endif
                                    </td>

                                    {{-- Source --}}
                                    <td>{{ Str::limit($cctv->source, 40) }}</td>

                                    {{-- Notes --}}
                                    <td>{{ Str::limit($cctv->notes, 25) ?: '-' }}</td>

                                    <td style="width: 200px">
                                        <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#editModal-{{ $cctv->id }}">
                                            <i class="bi bi-pencil-square"></i> Edit
                                        </button>

                                        <form action="{{ route('cctv.destroy', $cctv->id) }}" method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button onclick="return confirm('Delete CCTV?')" class="btn btn-danger btn-sm">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </form>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer d-flex justify-content-center">
                {{ $cctvs->links() }}
            </div>
        </div>
    </div>

    {{-- Add Modal --}}
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('cctv.store') }}" method="POST" class="modal-content">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Add CCTV</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Location</label>
                        <input type="text" name="location" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Source URL</label>
                        <input type="text" name="source" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Type</label>
                        <select name="type" class="form-control">
                            <option value="">-- Select CCTV Type --</option>
                            <option value="IP Camera">IP Camera</option>
                            <option value="Analog Camera">Analog Camera</option>
                            <option value="Dome Camera">Dome Camera</option>
                            <option value="Bullet Camera">Bullet Camera</option>
                            <option value="PTZ Camera">PTZ Camera</option>
                            <option value="Wireless Camera">Wireless Camera</option>
                            <option value="Thermal Camera">Thermal Camera</option>
                            <option value="360 Fisheye Camera">360° Fisheye Camera</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status Online</label>
                        <select name="online" class="form-select">
                            <option value="1">Online</option>
                            <option value="0">Offline</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control"></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary"><i class="bi bi-save"></i> Save</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Edit Modals --}}
    @foreach ($cctvs as $cctv)
        <div class="modal fade" id="editModal-{{ $cctv->id }}" tabindex="-1">
            <div class="modal-dialog">
                <form action="{{ route('cctv.update', $cctv->id) }}" method="POST" class="modal-content">
                    @csrf
                    @method('PUT')

                    <div class="modal-header">
                        <h5 class="modal-title">Edit CCTV</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">


                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" value="{{ $cctv->name }}" class="form-control"
                                required>
                        </div>


                        <div class="mb-3">
                            <label class="form-label">Location</label>
                            <input type="text" name="location" value="{{ $cctv->location }}" class="form-control"
                                required>
                        </div>


                        <div class="mb-3">
                            <label class="form-label">Source URL</label>
                            <input type="text" name="source" value="{{ $cctv->source }}" class="form-control"
                                required>
                        </div>


                        <div class="mb-3">
                            <label class="form-label">Type</label>
                            <select name="type" class="form-control">
                                <option value="">-- Select CCTV Type --</option>
                                <option value="IP Camera">IP Camera</option>
                                <option value="Analog Camera">Analog Camera</option>
                                <option value="Dome Camera">Dome Camera</option>
                                <option value="Bullet Camera">Bullet Camera</option>
                                <option value="PTZ Camera">PTZ Camera</option>
                                <option value="Wireless Camera">Wireless Camera</option>
                                <option value="Thermal Camera">Thermal Camera</option>
                                <option value="360 Fisheye Camera">360° Fisheye Camera</option>
                            </select>
                        </div>


                        <div class="mb-3">
                            <label class="form-label">Status Online</label>
                            <select name="online" class="form-select">
                                <option value="1" {{ $cctv->online ? 'selected' : '' }}>Online</option>
                                <option value="0" {{ !$cctv->online ? 'selected' : '' }}>Offline</option>
                            </select>
                        </div>


                        <div class="mb-3">
                            <label class="form-label">Notes</label>
                            <textarea name="notes" class="form-control" rows="3">{{ $cctv->notes }}</textarea>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button class="btn btn-success">
                            <i class="bi bi-check-lg"></i> Update
                        </button>
                    </div>

                </form>
            </div>
        </div>
    @endforeach


    {{-- View List --}}
        <div class="container cctv-wrapper mt-4">
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
