@extends('suggestions.layout')

@section('content')
    <div class="container-fluid px-4 mt-4">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">📋 Employee Suggestions</h4>
            <span class="text-muted small">{{ $suggestions->count() }} items</span>
        </div>

        {{-- FILTER + SEARCH --}}
        <div class="row mb-3">
            <div class="col-md-4">
                <input type="text" id="searchInput" class="form-control" placeholder="🔍 Search title or employee...">
            </div>
            <div class="col-md-3">
                <select id="statusFilter" class="form-select">
                    <option value="">All Status</option>
                    <option value="new">New</option>
                    <option value="read">Read</option>
                    <option value="in_progress">In Progress</option>
                    <option value="resolved">Resolved</option>
                </select>
            </div>
        </div>

        <div class="row">

            {{-- LEFT : LIST / GRID --}}
            <div class="col-md-4">
                <div class="row g-3" id="suggestionList">

                    @foreach ($suggestions as $suggestion)
                        <div class="col-12 suggestion-item" data-title="{{ strtolower($suggestion->title) }}"
                            data-employee="{{ strtolower($suggestion->worker->fullname ?? '') }}"
                            data-status="{{ $suggestion->status }}">

                            <div class="card shadow border-0 suggestion-card" onclick="showDetail({{ $suggestion->id }})"
                                style="cursor:pointer">

                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="fw-semibold mb-1">
                                            {{ $suggestion->title }}
                                        </h6>
                                        <span
                                            class="badge
                                    @if ($suggestion->status == 'new') bg-primary
                                    @elseif($suggestion->status == 'read') bg-info
                                    @elseif($suggestion->status == 'in_progress') bg-warning
                                    @else bg-success @endif">
                                            {{ str_replace('_', ' ', $suggestion->status) }}
                                        </span>
                                    </div>

                                    <div class="small text-muted">
                                        {{ $suggestion->worker->fullname ?? 'Unknown' }}
                                    </div>
                                </div>
                            </div>

                        </div>
                    @endforeach

                </div>
            </div>

            {{-- RIGHT : DETAIL --}}
            <div class="col-md-8">
                <div id="detailBox" class="card shadow border-0">
                    <div class="card-body p-3 text-center text-muted" style="min-height:200px;">
                        <i class="bi bi-arrow-left"></i>
                        Select a suggestion to view details
                    </div>
                </div>
            </div>


        </div>
    </div>

    {{-- DETAIL TEMPLATE --}}
    @foreach ($suggestions as $suggestion)
        <div id="detail-{{ $suggestion->id }}" class="d-none">
            <div class="p-3">

                <h5 class="fw-semibold mb-2">{{ $suggestion->title }}</h5>

                <div class="text-muted small mb-3">
                    {{ $suggestion->category }} •
                    {{ $suggestion->worker->fullname ?? 'Unknown' }}
                    ({{ $suggestion->employee_id }})
                </div>

                <p class="mb-3">{{ $suggestion->description }}</p>

                {{-- Attachment --}}
                @if ($suggestion->attachment_path)
                    <div class="text-center my-3">
                        @if ($suggestion->attachment_type === 'image')
                            <img src="{{ asset('storage/' . $suggestion->attachment_path) }}"
                                class="img-fluid rounded shadow-sm d-block mx-auto mb-3"
                                style="max-width:100%; max-height:350px; width:auto; object-fit:contain;">
                        @else
                            <video controls class="w-100 rounded mb-3" style="max-height:350px;">
                                <source src="{{ asset('storage/' . $suggestion->attachment_path) }}">
                            </video>
                        @endif
                    </div>
                @endif



                {{-- Feedback --}}
                @if ($suggestion->feedbacks->count())
                    <hr class="my-2">
                    <h6 class="fw-semibold mb-2">🛠 Admin Feedback</h6>
                    @foreach ($suggestion->feedbacks as $fb)
                        <div class="alert alert-light border-start border-4 border-info py-1 px-2 mb-2">
                            {{ $fb->feedback }}
                            <div class="small text-muted mt-1">
                                {{ $fb->created_at->format('d M Y H:i') }}
                            </div>
                        </div>
                    @endforeach
                @endif

                {{-- Form --}}
                <hr class="my-2">
                <form action="{{ route('suggestions.feedback', $suggestion->id) }}" method="POST">
                    @csrf
                    <textarea name="feedback" class="form-control mb-2" rows="3" placeholder="Write admin feedback..." required></textarea>
                    <button class="btn btn-success btn-sm mb-2 ms-1">
                        <i class="bi bi-send"></i> Send Feedback
                    </button>
                </form>

            </div>
        </div>
        <div class="d-flex justify-content-end mt-3 me-4">
            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>
    @endforeach


    {{-- SCRIPT --}}
    <script>
        function showDetail(id) {
            document.getElementById('detailBox').innerHTML =
                document.getElementById('detail-' + id).innerHTML;
        }

        // Search + Filter
        document.getElementById('searchInput').addEventListener('keyup', filterData);
        document.getElementById('statusFilter').addEventListener('change', filterData);

        function filterData() {
            let search = document.getElementById('searchInput').value.toLowerCase();
            let status = document.getElementById('statusFilter').value;

            document.querySelectorAll('.suggestion-item').forEach(item => {
                let matchSearch =
                    item.dataset.title.includes(search) ||
                    item.dataset.employee.includes(search);

                let matchStatus =
                    status === '' || item.dataset.status === status;

                item.style.display = (matchSearch && matchStatus) ? '' : 'none';
            });
        }
    </script>
@endsection
