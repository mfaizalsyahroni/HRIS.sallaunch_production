@extends('survey.layout')

@section('content')
    <div class="card shadow">
        <div class="card-header-blue">➕ Tambah Pertanyaan – {{ $survey->title }}</div>

        <div class="card-body">
            <form action="{{ route('survey.question.store', $survey->id) }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label fw-bold">Pertanyaan</label>
                    <input type="text" name="question" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Tipe</label>
                    <select name="type" class="form-control" required>
                        <option value="text">Text</option>
                        <option value="radio">Radio</option>
                        <option value="checkbox">Checkbox</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Opsi (pisahkan dengan koma, untuk radio/checkbox)</label>
                    <input type="text" name="options" class="form-control" placeholder="Option A, Option B, Option C">
                </div>

                <button class="btn btn-primary">Simpan Pertanyaan</button>
                <a href="{{ route('survey.questions', $survey->id) }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
@endsection
