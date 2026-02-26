@extends('layouts.app')

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
    integrity="sha512-S...HASH..." crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


@section('content')
    <div class="container">

        <h2 class="mb-4">Program Innovation Lead — Review Ideas</h2>

        @if (session('success'))
            <div class="alert alert-success text-center">
                {{ session('success') }}
            </div>
        @endif

        @foreach ($ideas as $idea)
            <div class="card mb-4 shadow-sm">
                <div class="card-body">

                    <h4>{{ $idea->title }}</h4>

                    <hr>
                    Employee ID: {{ $idea->user_id }} <br>

                    @if ($idea->worker)
                        ✅ Worker found: {{ $idea->worker->fullname }} ({{ $idea->worker->employee_id }})
                    @else
                        ❌ Worker NULL
                    @endif
                    <hr>

                    <p class="text-muted mb-2">
                        Votes: <strong>{{ $idea->votes_count }}</strong>
                    </p>

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

                                    <div class="mb-2">
                                        <strong><i class="bi bi-graph-up-arrow me-1 text-info"></i> Impact:</strong>
                                        <div class="ms-3">{!! $idea->impact !!}</div>
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


                    <form method="POST" action="{{ route('idea.review', $idea) }}">
                        @csrf

                        <div class="row">

                            <div class="col-md-3 mb-2">
                                <label>Business Impact (1–5)</label>
                                <input type="number" name="business_impact" class="form-control" min="1"
                                    max="5" required>
                            </div>

                            <div class="col-md-3 mb-2">
                                <label>Feasibility (1–5)</label>
                                <input type="number" name="feasibility" class="form-control" min="1" max="5"
                                    required>
                            </div>

                            <div class="col-md-3 mb-2">
                                <label>Sustainability (1–5)</label>
                                <input type="number" name="sustainability" class="form-control" min="1"
                                    max="5" required>
                            </div>

                            <div class="col-md-12 mt-2">
                                <label>Notes (optional)</label>
                                <textarea name="notes" class="form-control" rows="2" placeholder="Reviewer notes..."></textarea>
                            </div>

                        </div>

                        <button type="submit" class="btn btn-primary mt-3">
                            Submit Review
                        </button>

                    </form>

                </div>
            </div>
        @endforeach

        <a href="{{ route('idea.winner') }}" class="btn btn-success">
            See Final Ranking
        </a>

    </div>
@endsection
