@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>



<h2>Admin Dashboard</h2>

@foreach($ideas as $idea)
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
                        <button class="btn btn-sm btn-primary d-flex align-items-center gap-1 edit-btn"
                            data-id="{{ $module->id }}"
                            data-name="{{ $module->module_name }}"
                            data-category="{{ $module->category }}"
                            data-youtube="{{ $module->youtube_id }}"
                            data-duration="{{ $module->duration }}"
                            data-description="{{ $module->description }}">
                            <i class="bi bi-pencil"></i>
                            <span>Edit</span>
                        </button>
    
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
                        <a href="{{ asset('storage/' . $feedback->feedback_video) }}"
                            target="_blank"
                            class="btn btn-sm btn-success d-flex align-items-center gap-1">
                            <i class="bi bi-play-circle"></i>
                            <span>View</span>
                        </a>
                    @else
                        <span class="text-muted">No Feedback</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
@endforeach

@endsection