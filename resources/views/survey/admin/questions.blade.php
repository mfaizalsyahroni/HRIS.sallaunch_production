@extends('survey.layout')

@section('content')
    <div class="card p-4 shadow-sm">

        {{-- TITLE --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="mb-0">
                <i class="bi bi-question-circle me-1"></i>
                Survey Questions: {{ $survey->survey_name }}
            </h3>
        </div>

        {{-- ADD QUESTION FORM --}}
        <form method="POST" action="{{ route('admin.question.add') }}" class="mb-4" autocomplete="off">
            @csrf
            <input type="hidden" name="survey_id" value="{{ $survey->id }}">

            <div class="row g-3">

                {{-- QUESTION --}}
                <div class="col-md-6">
                    <label class="form-label">
                        <i class="bi bi-chat-text"></i> Question
                    </label>
                    <input type="text" class="form-control" name="question" required placeholder="Enter question" autocomplete="off">
                </div>

                {{-- TYPE --}}
                <div class="col-md-4">
                    <label class="form-label">
                        <i class="bi bi-ui-checks-grid"></i> Question Type
                    </label>
                    <select class="form-select" name="type" required id="question-type">
                        <option value="" selected disabled>-- Select Question Type --</option>
                        <option value="text">Text</option>
                        <option value="radio">Radio</option>
                        <option value="checkbox">Checkbox</option>
                    </select>
                </div>

                {{-- SUBMIT --}}
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-plus-circle"></i> Add
                    </button>
                </div>

                {{-- OPTIONS --}}
                <div class="col-md-12" id="option-box">
                    <label class="form-label">
                        <i class="bi bi-list-ul"></i> Answer Options
                    </label>

                    <div id="options-wrapper">
                        <input type="text" name="options[]" class="form-control mb-2" placeholder="Option 1" autocomplete="off">
                    </div>

                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="addOption()">
                        <i class="bi bi-plus"></i> Add Option
                    </button>
                </div>

            </div>
        </form>

        <hr>

        {{-- QUESTION LIST --}}
        <table class="table table-bordered align-middle">
            <tbody>
                @foreach ($survey->questions as $q)
                    <tr>
                        <td>
                            <strong class="d-block">{{ $q->question }}</strong>
                            <span class="badge bg-info mt-1">{{ strtoupper($q->type) }}</span>

                            <div class="mt-2">
                                @foreach ($q->options as $opt)
                                    <div class="d-flex align-items-center gap-2">
                                        @if ($q->type === 'radio')
                                            <i class="bi bi-record-circle text-primary"></i>
                                        @elseif ($q->type === 'checkbox')
                                            <i class="bi bi-square text-primary"></i>
                                        @else
                                            <i class="bi bi-dot text-secondary"></i>
                                        @endif
                                        <span>{{ $opt->option_text }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </td>

                        {{-- ACTION --}}
                        <td width="200">
                            <div class="row g-1">
                                <div class="col-6">
                                    <a href="{{ route('admin.question.edit', $q->id) }}"
                                        class="btn btn-sm btn-primary w-100">
                                        <i class="bi bi-pencil-square"></i>Edit
                                    </a>
                                </div>

                                <div class="col-6">
                                    <form action="{{ route('admin.question.delete', $q->id) }}" method="POST"
                                        onsubmit="return confirm('Delete this question?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger w-100">
                                            <i class="bi bi-trash"></i>Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- BACK BUTTON --}}
        <div class="d-flex justify-content-end mt-3">
            <a href="{{ route('survey.dashboard') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Back
            </a>
        </div>

    </div>

    {{-- SCRIPT --}}
    <script>
        const typeSelect = document.getElementById('question-type');
        const optionBox = document.getElementById('option-box');
        const wrapper = document.getElementById('options-wrapper');

        function toggleOption() {
            if (typeSelect.value === 'radio' || typeSelect.value === 'checkbox') {
                optionBox.style.display = 'block';
            } else {
                optionBox.style.display = 'none';
            }
        }

        function addOption() {
            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'options[]';
            input.className = 'form-control mb-2';
            input.placeholder = 'Option ' + (wrapper.children.length + 1);
            wrapper.appendChild(input);
        }

        typeSelect.addEventListener('change', toggleOption);
        toggleOption();
    </script>
@endsection
