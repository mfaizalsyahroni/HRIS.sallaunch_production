@extends('layouts.app')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-S...HASH..." crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
    <div class="container py-4">

        {{-- Alert --}}
        @if (session('updated'))
            <div class="alert alert-success">✓ {{ session('updated') }}</div>
        @endif
        @foreach ($errors->all() as $error)
            <div class="alert alert-danger">{{ $error }}</div>
        @endforeach

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center border rounded-3 pb-3 mb-4">
            <div class="mb-0 fw-semibold" style="font-size: 20px">📘 Course Learning Plan — MT Course Setup</div>
            <div class="d-flex align-items-center gap-3">
                <span class="badge bg-secondary fw-bold">Management Trainee Access</span>
            </div>
        </div>

        {{-- Loop per level --}}
        @foreach (['competency' => 'Level 1 — Competency', 'proficiency' => 'Level 2 — Proficiency', 'mastery' => 'Level 3 — Mastery'] as $key => $label)
            <p class="text-uppercase text-secondary fw-bold small mb-2" style="letter-spacing:1.2px">{{ $label }}</p>

            @if (isset($grouped[$key]) && $grouped[$key]->count())
                <div class="card border rounded-4 overflow-hidden mb-4">
                    <table class="table table-bordered table-hover align-middle mb-0">
                        <thead class="table-secondary bg-gradient">
                            <tr>
                                <th class="text-secondary small fw-semibold">No.</th>
                                <th class="text-secondary small fw-semibold">Module Name</th>
                                <th class="text-secondary small fw-semibold">Category</th>
                                <th class="text-secondary small fw-semibold">Duration</th>
                                <th class="text-secondary small fw-semibold">Certificate</th>
                                <th class="text-secondary small fw-semibold">Action</th>
                            </tr>
                        </thead>
                        <tbody border-1>
                            @foreach ($grouped[$key] as $i => $module)
                                <tr>
                                    <td class="text-secondary">{{ $i + 1 }}.</td>
                                    <td><strong>{{ $module->module_name }}</strong></td>
                                    <td>{{ $module->category }}</td>
                                    <td>{{ $module->duration }}</td>
                                    <td>
                                        @php
                                            // Pastikan nama variabel di sini adalah $badgeStyle
                                            $badgeStyle = match ($module->certificate_title) {
                                                'mastery'
                                                    => 'background-color: #FFD700; color: #333; font-weight: bold;', // Emas (Juara 1)
                                                'proficiency'
                                                    => 'background-color: #C0C0C0; color: #333;', // Perak (Juara 2)
                                                default
                                                    => 'background-color: #CD7F32; color: white;', // Perunggu (Juara 3)
                                            };
                                        @endphp

                                        {{-- Panggil variabel yang sama: $badgeStyle --}}
                                        <span class="badge"
                                            style="{{ $badgeStyle }} padding: 5px 10px; border-radius: 4px;">
                                            {{ ucfirst($module->certificate_title) }}
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-secondary"
                                            onclick="toggleEdit({{ $module->id }})">Edit</button>
                                    </td>
                                </tr>

                                {{-- Form Edit --}}
                                <tr id="edit-{{ $module->id }}" class="d-none">
                                    <td colspan="6" class="bg-light p-3">
                                        <p class="fw-semibold small mb-3">Edit Module: {{ $module->module_name }}</p>
                                        <form method="POST" action="{{ route('learningplan.admin.dashboard') }}">
                                            @csrf
                                            <input type="hidden" name="module_id" value="{{ $module->id }}">

                                            <div class="row g-3">
                                                <div class="col-md-6">
                                                    <label class="form-label small fw-semibold text-secondary">Module
                                                        Name</label>
                                                    <input type="text" name="module_name"
                                                        class="form-control form-control-sm"
                                                        value="{{ $module->module_name }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label
                                                        class="form-label small fw-semibold text-secondary">Category</label>
                                                    <input type="text" name="category"
                                                        class="form-control form-control-sm"
                                                        value="{{ $module->category }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label small fw-semibold text-secondary">YouTube
                                                        ID</label>
                                                    <input type="text" name="youtube_id"
                                                        class="form-control form-control-sm"
                                                        value="{{ $module->youtube_id }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label
                                                        class="form-label small fw-semibold text-secondary">Duration</label>
                                                    <input type="text" name="duration"
                                                        class="form-control form-control-sm"
                                                        value="{{ $module->duration }}" required>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label small fw-semibold text-secondary">Certificate
                                                        Title</label>
                                                    <select name="certificate_title" class="form-select form-select-sm"
                                                        required>
                                                        @foreach (['competency', 'proficiency', 'mastery'] as $opt)
                                                            <option value="{{ $opt }}"
                                                                {{ $module->certificate_title === $opt ? 'selected' : '' }}>
                                                                {{ ucfirst($opt) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-12">
                                                    <label
                                                        class="form-label small fw-semibold text-secondary">Description</label>
                                                    <textarea name="description" class="form-control form-control-sm" rows="2">{{ $module->description }}</textarea>
                                                </div>
                                            </div>

                                            <div class="mt-3 d-flex gap-2">
                                                <button type="submit" class="btn btn-sm btn-dark">Save Changes</button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary"
                                                    onclick="toggleEdit({{ $module->id }})">Cancel</button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center text-secondary small py-4 border rounded bg-white mb-4">
                    No modules for this level yet.
                </div>
            @endif
        @endforeach

    </div>

    <script>
        function toggleEdit(id) {
            const row = document.getElementById('edit-' + id);
            row.classList.toggle('d-none');
        }
    </script>
@endsection
