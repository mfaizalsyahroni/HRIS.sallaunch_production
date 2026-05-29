    @extends('layouts.app')

    @section('content')
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
            integrity="sha512-S...HASH..." crossorigin="anonymous" referrerpolicy="no-referrer" />
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        {{-- Background Layer --}}
        <div
            style="
                        position: fixed;
                        top: 0; left: 0;
                        width: 100vw; height: 100vh;
                        background-color: #f0f2f5;
                        z-index: -1;
                        ">
        </div>

        <div class="my-4">
            <h2 class="fw-bold mb-4">
                <i class="bi bi-display me-2"></i>Admin Dashboard
            </h2>

            <div class="text-center">
                @if (session('created'))
                    <div class="alert alert-success flash-message"
                        style="display: inline-block; background-color: #f8d7da; color: #20847a; padding: 4px 8px; border-radius: 6px;">
                        <i class="bi bi-check-circle-fill me-1"></i>
                        {{ session('created') }}
                    </div>
                @endif

                @if (session('updated'))
                    <div class="alert flash-message d-inline-block"
                        style="background-color: #f8d7da; color: #046585; padding: 4px 8px; border-radius: 6px;">
                        <i class="bi bi-pencil-square me-1"></i>
                        {{ session('updated') }}
                    </div>
                @endif

                @if (session('deleted'))
                    <div class="alert alert-danger flash-message"
                        style="display: inline-block; background-color: #f8d7da; color: #842029; padding: 4px 8px; border-radius: 6px;">
                        >
                        <i class="bi bi-trash3-fill me-1"></i>
                        {{ session('deleted') }}
                    </div>
                @endif
            </div>

            <script>
                document.querySelectorAll('.flash-message').forEach(el => {
                    setTimeout(() => {
                        el.remove();
                    }, 8000);
                });
            </script>



            <div class="container bg-light bg-gradient mb-4  rounded-3" style="width: 35%; margin-left: 0;">
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-people-fill text-primary fs-4 me-2"></i>
                    <span class="fw-bold me-2">Total Staff:</span> {{ $totalStaff }}
                </div>
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-box-seam-fill text-success fs-4 me-2"></i>
                    <span class="fw-bold me-2">Total Modules:</span> {{ $totalModules }}
                </div>
                <div class="d-flex align-items-center">
                    <i class="bi bi-chat-dots-fill text-warning fs-4 me-2"></i>
                    <span class="fw-bold me-2">Total Feedback:</span> {{ $totalFeedback }}
                </div>
            </div>

            <!-- Form Tambah/Edit Modul -->
            <form method="POST" action="{{ route('learningplan.admin.dashboard') }}" class="mb-4">
                @csrf
                <input type="hidden" name="module_id" id="module_id">
                <div class="row g-2">

                    <div class="row">
                        <div class="col-md-4">
                            <label for="certificate_title" class="form-label fw-bold">Select Certificate Type</label>
                            <select name="certificate_title" id="certificate_title" class="form-select" required>
                                <option value="" disabled selected>-- Certificate Type --</option>
                                <option value="competency">🎓 Certificate of Competency</option>
                                <option value="proficiency">🎓 Certificate of Proficiency</option>
                                <option value="mastery">🎓 Certificate of Mastery</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-2">
                            <input type="text" name="module_name" id="module_name" class="form-control"
                                placeholder="Module Name" required>
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="category" id="category" class="form-control" placeholder="Category"
                                required>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="description" id="description" class="form-control"
                                placeholder="Description">
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="youtube_id" id="youtube_id" class="form-control"
                                placeholder="YouTube ID" required>
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="duration" id="duration" class="form-control" placeholder="Duration"
                                required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-outline-success fw-bold">
                                <i class="bi bi-floppy"></i> Save Data
                            </button>
                        </div>
                    </div>

                </div>
            </form>

            <!-- Tabel Modul -->
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-secondary">
                    <tr>
                        <th class="text-nowrap">ID</th>
                        <th class="text-nowrap">Module Name</th>
                        <th class="text-nowrap">Category</th>
                        <th class="text-nowrap">Certificate</th>
                        <th class="text-nowrap">YouTube ID</th>
                        <th class="text-nowrap">Duration</th>
                        <th class="text-nowrap">Description</th>
                        <th class="text-nowrap">Actions</th>
                        <th class="text-nowrap">Feedback</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($modules as $module)
                        <tr>
                            <td class="fw-bold">{{ $module->id }}.</td>
                            <td class="small">{{ $module->module_name }}</td>
                            <td class="small">{{ $module->category }}</td>
                            <td>
                                @php
                                    $certColors = [
                                        'competency' => 'bg-primary',
                                        'proficiency' => 'bg-warning text-dark',
                                        'mastery' => 'bg-danger',
                                    ];
                                    $certColor = $certColors[$module->certificate_title] ?? 'bg-secondary';
                                @endphp
                                <span class="badge {{ $certColor }}">
                                    {{ $module->certificate_title_label }}
                                </span>
                            </td>
                            <td class="small">{{ $module->youtube_id }}</td>
                            <td class="small">{{ $module->duration }}</td>
                            <td class="small">{{ $module->description }}</td>
                            <td>
                                <div class="d-grid gap-2">
                                    <!-- Edit Button -->
                                    <button class="btn btn-sm btn-primary d-flex align-items-center gap-1 edit-btn"
                                        data-id="{{ $module->id }}" data-name="{{ $module->module_name }}"
                                        data-category="{{ $module->category }}" data-youtube="{{ $module->youtube_id }}"
                                        data-duration="{{ $module->duration }}"
                                        data-description="{{ $module->description }}"
                                        data-cert="{{ $module->certificate_title }}">
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

                                @if ($module->latestFeedback)
                                    <button type="submit"
                                        class="btn btn-sm btn-success d-flex align-items-center gap-1"
                                        data-bs-toggle="modal"
                                        data-bs-target="#videoModal"
                                        data-video-url="{{ asset('storage/' . $module->latestFeedback->feedback_video) }}"
                                        data-module-name="{{ $module->module_name }}">
                                        <i class="bi bi-play-circle"></i> <span>View</span>
                                    </button>
                                @else
                                    <span class="text-muted">No Feedback</span>
                                @endif

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="d-flex justify-content-center mt-4">
                {{-- replace with actual logout form --}}
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
                    document.getElementById('certificate_title').value = this.dataset.cert;
                    window.scrollTo(0, 0); // scroll ke form
                });
            });
        </script>
    @endsection
