    @extends('layouts.app')

    @section('content')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
            integrity="sha512-S...HASH..." crossorigin="anonymous" referrerpolicy="no-referrer" />


        <div class="container py-5 bg-light rounded-3">
            <h2 class="fw-bold mb-4">📊 Admin Dashboard</h2>

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="mb-4">
                <p>Total Staff: {{ $totalStaff }}</p>
                <p>Total Modules: {{ $totalModules }}</p>
                <p>Total Feedback: {{ $totalFeedback }}</p>
            </div>

            <!-- Form Tambah/Edit Modul -->
            <form method="POST" action="{{ route('learningplan.admin.dashboard') }}" class="mb-4">
                @csrf
                <input type="hidden" name="module_id" id="module_id">
                <div class="row g-2">
                    <div class="col-md-2"><input type="text" name="module_name" id="module_name" class="form-control"
                            placeholder="Module Name" required></div>
                    <div class="col-md-2"><input type="text" name="category" id="category" class="form-control"
                            placeholder="Category" required></div>
                    <div class="col-md-2"><input type="text" name="youtube_id" id="youtube_id" class="form-control"
                            placeholder="YouTube ID" required></div>
                    <div class="col-md-2"><input type="text" name="duration" id="duration" class="form-control"
                            placeholder="Duration" required></div>
                    <div class="col-md-3"><input type="text" name="description" id="description" class="form-control"
                            placeholder="Description"></div>
                    <div class="col-md-1"><button class="btn btn-success w-100">Save</button></div>
                </div>
            </form>

            <!-- Tabel Modul -->
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Module Name</th>
                        <th>Category</th>
                        <th>YouTube ID</th>
                        <th>Duration</th>
                        <th>Description</th>
                        <th>Actions</th>
                        <th>Feedback</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($modules as $module)
                        <tr>
                            <td>{{ $module->id }}</td>
                            <td>{{ $module->module_name }}</td>
                            <td>{{ $module->category }}</td>
                            <td>{{ $module->youtube_id }}</td>
                            <td>{{ $module->duration }}</td>
                            <td>{{ $module->description }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <!-- Edit Button -->
                                    <button class="btn btn-sm btn-primary d-flex align-items-center gap-1 edit-btn"
                                        data-id="{{ $module->id }}" data-name="{{ $module->module_name }}"
                                        data-category="{{ $module->category }}" data-youtube="{{ $module->youtube_id }}"
                                        data-duration="{{ $module->duration }}"
                                        data-description="{{ $module->description }}">
                                        <i class="bi bi-pencil"></i>
                                        <span>Edit</span>
                                    </button>

                                    <!-- Delete Button -->
                                    <form method="POST" action="{{ route('learningplan.admin.delete', $module->id) }}"
                                        onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger d-flex align-items-center gap-1">
                                            <i class="bi bi-trash3"></i>
                                            <span>Delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                            <td>
                                @php
                                    $feedback = \App\Models\LearningProgress::where('module_id', $module->id)
                                        ->latest()
                                        ->first();
                                @endphp

                                @if ($feedback)
                                    <a href="{{ asset('storage/' . $feedback->feedback_video) }}" target="_blank"
                                        class="btn btn-sm btn-success d-flex align-items-center gap-1">
                                        <i class="bi bi-play-circle"></i> <span>View</span>
                                    </a>
                                @else
                                    <span class="text-muted">No Feedback</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center mt-4">
                <form action="{{ route('overtime.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger px-4">
                        <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                    </button>
                </form>
            </div>
        </div>

        <!-- Script untuk edit -->
        <script>
            document.querySelectorAll('.edit-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.getElementById('module_id').value = this.dataset.id;
                    document.getElementById('module_name').value = this.dataset.name;
                    document.getElementById('category').value = this.dataset.category;
                    document.getElementById('youtube_id').value = this.dataset.youtube;
                    document.getElementById('duration').value = this.dataset.duration;
                    document.getElementById('description').value = this.dataset.description;
                    window.scrollTo(0, 0); // scroll ke form
                });
            });
        </script>
    @endsection
