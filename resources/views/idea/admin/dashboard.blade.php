@extends('layouts.ideastaff')

@section('content')
    <div class="container py-4">

        {{-- HEADER --}}
        <div class="mb-5">
            <h2 class="fw-bold mb-1">
                <i class="bi bi-graph-up-arrow text-primary me-2"></i>
                Innovation Control Center
            </h2>
            <small class="text-muted text-primary">
                Enterprise Innovation Monitoring Dashboard • {{ now()->format('d M Y') }}
            </small>
        </div>


        {{-- LOW ENGAGEMENT ALERT --}}
        @if ($lowEngagement)
            <div class="alert alert-warning border-0 shadow-sm">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                Participation rate is <strong>{{ $rate }}%</strong>.
                Consider internal reminder.
            </div>
        @endif


        {{-- EXECUTIVE METRICS (NO MINI CARDS) --}}
        <div class="mb-5">

            <div class="row text-center">

                <div class="col-md-3">
                    <h6 class="text-muted">Total Ideas</h6>
                    <h2 class="fw-bold">{{ $totalIdeas }}</h2>
                </div>

                <div class="col-md-3">
                    <h6 class="text-muted">Voting</h6>
                    <h2 class="fw-bold text-warning">{{ $voting }}</h2>
                </div>

                <div class="col-md-3">
                    <h6 class="text-muted">Reviewed</h6>
                    <h2 class="fw-bold text-success">{{ $reviewed }}</h2>
                </div>

                <div class="col-md-3">
                    <h6 class="text-muted">Employees Voted</h6>
                    <h2 class="fw-bold text-primary">{{ $workersVoted }}</h2>
                </div>

            </div>

        </div>


        {{-- TOP IDEA + ENGAGEMENT --}}
        <div class="row mb-5">

            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100 border-1">

                    <div class="card-body">

                        <h6 class="text-uppercase text-muted mb-3">
                            <i class="bi bi-trophy me-2 text-warning"></i>
                            Top Performing Idea
                        </h6>

                        @if ($topIdea && $topIdea->worker)
                            <div class="text-muted small mt-2">
                                <strong>{{ $topIdea->worker->fullname ?? 'Unknown' }}</strong>
                                ({{ $topIdea->worker->employee_id }})
                            </div>
                        @endif

                        <h5 class="fw-bold">
                            {{ $topIdea?->title ?? 'No Data Available' }}
                        </h5>

                        <span class="badge bg-primary mt-2">
                            {{ $topIdea?->votes_count ?? 0 }} Votes
                        </span>

                    </div>
                </div>
            </div>


            <div class="col-md-6 mb-4">
                <div class="card shadow-sm h-100 border-1">

                    <div class="card-body">

                        <h6 class="text-uppercase text-muted mb-3">
                            <i class="bi bi-people-fill me-2 text-primary"></i>
                            Employee Engagement
                        </h6>

                        <h3 class="fw-bold">{{ $rate }}%</h3>

                        <div class="progress mt-3" style="height:10px">
                            <div class="progress-bar
                        @if ($rate < 60) bg-danger
                        @elseif($rate < 80) bg-warning
                        @else bg-success @endif"
                                style="width: {{ $rate }}%">
                            </div>
                        </div>

                        <small class="text-muted d-block mt-2">
                            {{ $workersVoted }} of {{ $totalWorkers }} employees participated
                        </small>

                    </div>
                </div>
            </div>

        </div>


        {{-- NOT VOTED --}}
        <div class="mb-5">

            <div class="mb-3">

                <h5 class="fw-bold mb-1">
                    <i class="bi bi-person-x me-2 text-danger"></i>
                    Employees Who Have Not Voted
                </h5>

                <div class="ms-4">
                    <div
                        class="fw-bold fs-4 
            @if ($notVotedCount > 0) text-danger 
            @else text-success @endif">
                        {{ $notVotedCount }}
                    </div>

                    <small class="text-muted">Employees</small>
                </div>

            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="border-top">
                        <tr>
                            <th>Employee ID</th>
                            <th>Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($workersNotVoted as $worker)
                            <tr class="border-bottom">
                                <td>{{ $worker->employee_id }}</td>
                                <td>{{ $worker->fullname }}</td>
                                <td>
                                    {{-- <button type="submit" class="btn btn-outline-warning px-2">
                                        <i class="fa-solid fa-paper-plane"></i>
                                        <span style="margin-left: 2px;">
                                            Send Alert
                                        </span>
                                    </button> --}}

                                    @if ($worker->alert_sent)
                                        <button class="btn btn-success btn-sm" disabled>
                                            <i class="fa-solid fa-check"></i>
                                            Alert Sent
                                        </button>
                                    @else
                                        <form action="{{ route('idea.sendAlert', $worker->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-warning btn-sm">
                                                <i class="fa-solid fa-paper-plane"></i>
                                                Send Alert
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center text-success fw-bold">
                                    All employees have voted 🎉
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>


        {{-- ALL IDEAS TABLE --}}
        <div>

            <h5 class="fw-bold mb-3">
                <i class="bi bi-lightbulb me-2 text-warning"></i>
                All Submitted Ideas
            </h5>

            <div class="table-responsive">
                <table class="table table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Votes</th>
                            <th>Submitted</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($ideas as $idea)
                            <tr>
                                <td class="fw-semibold">{{ $idea->title }}</td>

                                <td>
                                    <span
                                        class="badge
                                    @if ($idea->status == 'draft') bg-secondary
                                    @elseif($idea->status == 'voting') bg-warning text-dark
                                    @elseif($idea->status == 'reviewed') bg-success
                                    @else bg-dark @endif">
                                        {{ ucfirst($idea->status) }}
                                    </span>
                                </td>

                                <td>{{ $idea->votes_count }}</td>

                                <td>{{ $idea->created_at->format('d M Y') }}</td>
                            </tr>
                        @endforeach

                    </tbody>

                </table>
            </div>

        </div>


        {{-- LOGOUT --}}
        <div class="text-center mt-5">
            <form action="{{ route('idea.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-danger px-4">
                    <i class="fa-solid fa-right-from-bracket me-2"></i> Logout
                </button>
            </form>
        </div>

    </div>
@endsection
