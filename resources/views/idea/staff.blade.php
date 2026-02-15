@extends('layouts.ideastaff')


@section('content')
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css"> --}}
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        integrity="sha512-S...HASH..." crossorigin="anonymous" referrerpolicy="no-referrer" /> --}}



    <div class="container py-4">

        <h2 class="mb-4 fw-bold">
            <i class="bi bi-speedometer2 me-2 text-primary"></i>
            Staff Idea
        </h2>

        {{-- ALERT SUCCESS --}}
        @if (session('success'))
            <div class="alert alert-success">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}
            </div>
        @endif

        {{-- ALERT ERROR --}}
        @if (session('error'))
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                {{ session('error') }}
            </div>
        @endif


        {{--  SUBMIT IDEA  --}}
        <div class="card shadow-sm mb-5 border-2">
            <div class="card-header bg-primary text-white">
                <strong>
                    <i class="bi bi-plus-circle-fill me-2"></i>
                    Submit New Idea
                </strong>
            </div>
            <div class="card-body">

                <form action="{{ route('idea.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-tag"></i> Title
                        </label>
                        <input type="text" name="title" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-exclamation-diamond me-1 text-danger"></i> Problem
                        </label>
                        <textarea name="problem" class="form-control summernote" rows="2" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-tools me-1 text-success"></i> Solution
                        </label>
                        <textarea name="solution" class="form-control summernote" rows="2" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-graph-up-arrow me-1 text-info"></i> Impact (Optional)
                        </label>
                        <textarea name="impact" class="form-control summernote" rows="2"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-paperclip me-1"></i> Attachment (Optional)
                        </label>
                        <input type="file" name="attachment" class="form-control">
                        <small class="text-muted">
                            <i class="bi bi-file-earmark-text me-1"></i>
                            PDF, DOC, PPT (Max 10MB)
                        </small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-camera-video me-1"></i> Demo Video (Optional)
                        </label>
                        <input type="file" name="demo_video" class="form-control">
                        <small class="text-muted">
                            <i class="bi bi-film me-1"></i>
                            MP4, MOV, AVI (Max 20MB)
                        </small>
                    </div>

                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-send-fill me-1"></i>
                        Submit Idea
                    </button>
                </form>
            </div>
        </div>



        {{--  MY IDEAS  --}}
        <div class="card shadow-sm mb-5 border-2">
            <div class="card-header bg-info text-white">
                <strong>
                    <i class="bi bi-lightbulb-fill me-2"></i>
                    My Ideas
                </strong>
            </div>
            <div class="card-body">

                @forelse($myIdeas as $idea)
                    <div class="border rounded p-3 mb-3">

                        <h5 class="fw-semibold">
                            <i class="bi bi-bookmark-star-fill me-2 text-warning"></i>
                            {{ $idea->title }}
                        </h5>

                        {{-- Display Problem with HTML rendering --}}
                        <div class="mb-2">
                            <strong><i class="bi bi-exclamation-diamond me-1 text-danger"></i> Problem:</strong>
                            <div class="ms-3">{!! $idea->problem !!}</div>
                        </div>

                        {{-- Display Solution with HTML rendering --}}
                        <div class="mb-2">
                            <strong><i class="bi bi-tools me-1 text-success"></i> Solution:</strong>
                            <div class="ms-3">{!! $idea->solution !!}</div>
                        </div>

                        {{-- Display Impact if exists --}}
                        @if ($idea->impact)
                            <div class="mb-2">
                                <strong><i class="bi bi-graph-up-arrow me-1 text-info"></i> Impact:</strong>
                                <div class="ms-3">{!! $idea->impact !!}</div>
                            </div>
                        @endif

                        <p class="mb-1">
                            <strong>
                                <i class="bi bi-flag-fill me-1 text-danger"></i> Status:
                            </strong>
                            <span class="badge bg-dark">
                                {{ ucfirst($idea->status) }}
                            </span>
                        </p>

                        @if ($idea->attachment)
                            <a href="{{ asset('storage/' . $idea->attachment) }}" target="_blank"
                                class="btn btn-sm btn-outline-primary me-2">
                                <i class="bi bi-paperclip me-1"></i>
                                View Attachment
                            </a>
                        @endif

                        @if ($idea->demo_video)
                            <a href="{{ asset('storage/' . $idea->demo_video) }}" target="_blank"
                                class="btn btn-sm btn-outline-success">
                                <i class="bi bi-play-circle me-1"></i>
                                View Demo Video
                            </a>
                        @endif

                        <p class="text-muted mt-2 mb-0">
                            <i class="bi bi-calendar-event me-1"></i>
                            Submitted: {{ $idea->created_at->format('d M Y') }}
                        </p>

                    </div>
                @empty
                    <div class="alert alert-light border">
                        <i class="bi bi-info-circle me-2"></i>
                        You haven't submitted any ideas yet.
                    </div>
                @endforelse

            </div>
        </div>


        {{--  VOTING IDEAS  --}}
        <div class="card shadow-sm border-2">
            <div class="card-header bg-warning text-dark">
                <strong>
                    <i class="bi bi-bar-chart-fill me-2"></i>
                    Ideas in Voting Phase
                </strong>
            </div>
            <div class="card-body">

                @forelse($votingIdeas as $idea)
                    <div class="border rounded p-3 mb-3">

                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <h6 class="fw-semibold mb-1">
                                <i class="bi bi-lightbulb me-2 text-primary"></i>
                                {{ $idea->title }}
                            </h6>

                            <form action="{{ route('idea.vote', $idea->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-warning btn-sm">
                                    <i class="bi bi-hand-thumbs-up-fill me-1"></i>
                                    Vote
                                </button>
                            </form>
                        </div>

                        {{-- Display Problem with HTML rendering --}}
                        <div class="mb-2">
                            <strong><i class="bi bi-exclamation-diamond me-1 text-danger"></i> Problem:</strong>
                            <div class="ms-3">{!! $idea->problem !!}</div>
                        </div>

                        {{-- Display Solution with HTML rendering --}}
                        <div class="mb-2">
                            <strong><i class="bi bi-tools me-1 text-success"></i> Solution:</strong>
                            <div class="ms-3">{!! $idea->solution !!}</div>
                        </div>

                        <small class="text-muted">
                            <i class="bi bi-hand-thumbs-up me-1 text-success"></i>
                            Total Votes: {{ $idea->votes_count }}
                        </small>

                        @if ($idea->attachment)
                            <div class="mt-2">
                                <a href="{{ asset('storage/' . $idea->attachment) }}" target="_blank"
                                    class="btn btn-sm btn-outline-primary me-2">
                                    <i class="bi bi-file-earmark-text me-1"></i>
                                    View Proposal
                                </a>
                            </div>
                        @endif

                    </div>
                @empty
                    <div class="alert alert-light border">
                        <i class="bi bi-exclamation-circle me-2"></i>
                        No ideas currently in voting.
                    </div>
                @endforelse

            </div>
        </div>
        <div class="text-center my-3">
            <form action="{{ route('idea.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-danger px-4 fw-bold">
                    <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                </button>
            </form>
        </div>
    </div>



@endsection
