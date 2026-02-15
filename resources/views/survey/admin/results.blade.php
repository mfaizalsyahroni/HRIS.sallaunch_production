@extends('survey.layout')

@section('content')
    <div class="card p-4 shadow">
        <h4>🧾 Survey Detail</h4>

        <p><strong>Survey:</strong> {{ $submission->survey->survey_name }}</p>
        <p><strong>Employee ID:</strong> {{ $submission->employee_id }}</p>
        <p><strong>Name:</strong> {{ $submission->fullname }}</p>
        <p><strong>Date:</strong> {{ $submission->survey_date_formatted }}</p>



        <hr>

        <ul class="list-group">
            @foreach ($submission->answers as $ans)
                <li class="list-group-item">
                    <strong>{{ $ans->question->question }}</strong><br>
                    {{ $ans->answer }}
                </li>
            @endforeach
        </ul>

        <a href="{{ route('admin.survey.results') }}" class="btn btn-secondary mt-3">
            Back
        </a>
    </div>
@endsection
