@extends('survey.layout')

@section('content')
    {{-- SUCCESS ALERT --}}
    @if (session('success'))
        <div id="successAlert" class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-1"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>

        <script>
            setTimeout(() => {
                const el = document.getElementById('successAlert');
                if (el) el.remove();
            }, 6000);
        </script>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">

            {{-- HEADER --}}
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="mb-0 fw-semibold">
                    <i class="bi bi-clipboard-data me-1 text-primary"></i>
                    Survey Management
                </h4>
            </div>

            {{-- ADD SURVEY --}}
            <form method="POST" action="{{ route('admin.survey.create') }}" class="mb-4">
                @csrf
                <div class="row g-2 align-items-center">
                    <div class="col-md-9">
                        <div class="input-group">
                            <span class="input-group-text bg-light">
                                <i class="bi bi-pencil-square"></i>
                            </span>
                            <input type="text" name="survey_name"
                                class="form-control @error('survey_name') is-invalid @enderror"
                                placeholder="Enter new survey name" autocomplete="off">
                            @error('survey_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-3 d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Create Survey
                        </button>
                    </div>
                </div>
            </form>

            {{-- TABLE --}}
            <div class="table-responsive">
                <table class="table table-bordered align-middle text-center small">
                    <thead class="table-light text-uppercase">
                        <tr>
                            <th class="text-start">Survey Name</th>
                            <th>Status</th>
                            <th>Questions</th>
                            <th>Results</th>
                            <th width="220">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($surveys as $s)
                            <tr>
                                <td class="text-start fw-medium">
                                    {{ $s->survey_name }}
                                </td>

                                {{-- STATUS --}}
                                <td>
                                    <span class="badge {{ $s->is_active ? 'bg-success' : 'bg-secondary' }}">
                                        <i class="bi {{ $s->is_active ? 'bi-check-circle' : 'bi-x-circle' }}"></i>
                                        {{ $s->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>

                                {{-- QUESTIONS --}}
                                <td>
                                    <a href="{{ route('admin.survey.questions', $s->id) }}"
                                        class="btn btn-sm btn-outline-info">
                                        <i class="bi bi-list-ul"></i> Manage
                                    </a>
                                </td>

                                {{-- RESULTS --}}
                                <td>
                                    <a href="{{ route('admin.survey.results', $s->id) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-bar-chart-fill"></i> View
                                    </a>
                                </td>

                                {{-- ACTIONS --}}
                                <td>
                                    <div class="d-flex justify-content-center gap-2">

                                        {{-- PUBLISH --}}
                                        @if (!$s->is_active)
                                            <form action="{{ route('survey.publish', $s->id) }}" method="POST">
                                                @csrf
                                                <button class="btn btn-sm btn-outline-success">
                                                    <i class="bi bi-eye me-1"></i> Publish
                                                </button>
                                            </form>
                                        @endif

                                        @if ($s->is_active)
                                            <form action="{{ route('survey.publish', $s->id) }}" method="POST">
                                                @csrf
                                                <button class="btn btn-sm btn-outline-warning">
                                                    <i class="bi bi-eye-slash me-1"></i> Unpublish
                                                </button>
                                            </form>
                                        @endif



                                        {{-- DELETE --}}
                                        <form action="{{ route('admin.survey.delete', $s->id) }}" method="POST"
                                            onsubmit="return confirm('Delete this survey?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash me-1"></i> Delete
                                            </button>
                                        </form>


                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-muted py-4">
                                    No survey data available
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- FOOTER ACTION --}}
            <div class="d-flex justify-content-end mt-3">
                <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back
                </a>
            </div>


        </div>
    </div>
@endsection
