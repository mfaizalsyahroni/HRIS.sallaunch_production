@extends('survey.layout')

@section('content')
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card p-4 shadow">
        <h3 class="mb-3">Edit Pertanyaan</h3>

        <form method="POST" action="{{ route('admin.question.update', $question->id) }}">
            @csrf
            @method('PUT')

            <input type="hidden" name="survey_id" value="{{ $question->survey_id }}">

            {{-- QUESTION --}}
            <div class="mb-3">
                <label class="form-label">Pertanyaan</label>
                <input type="text" class="form-control" name="question"
                    value="{{ old('question', $question->question) }}" required>
            </div>

            {{-- TYPE --}}
            <div class="mb-3">
                <label class="form-label">Tipe</label>
                <select class="form-control" name="type" id="question-type" required>
                    <option value="text" {{ old('type', $question->type) == 'text' ? 'selected' : '' }}>Text</option>
                    <option value="radio" {{ old('type', $question->type) == 'radio' ? 'selected' : '' }}>Radio</option>
                    <option value="checkbox" {{ old('type', $question->type) == 'checkbox' ? 'selected' : '' }}>Checkbox
                    </option>
                </select>
            </div>

            {{-- OPTIONS --}}
            <div class="mb-3" id="option-box">
                <label class="form-label">Opsi Jawaban</label>

                <div id="options-wrapper">
                    @foreach ($question->options as $opt)
                        <input type="text" class="form-control mb-2" name="options[]" value="{{ $opt->option_text }}">
                    @endforeach
                </div>

                <button type="button" class="btn btn-sm btn-secondary" onclick="addOption()">
                    + Tambah Opsi
                </button>
            </div>

            {{-- ACTION --}}
            <div class="d-flex gap-2 mt-3">
                <button class="btn btn-primary">Update</button>

                <a href="{{ route('admin.survey.questions', $question->survey_id) }}" class="btn btn-secondary">
                    Kembali
                </a>
            </div>
        </form>
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
            input.placeholder = 'Opsi baru';
            wrapper.appendChild(input);
        }

        typeSelect.addEventListener('change', toggleOption);
        toggleOption();
    </script>
@endsection
