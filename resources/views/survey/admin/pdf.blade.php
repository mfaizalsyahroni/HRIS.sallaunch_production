<h3>Hasil Survey</h3>

<p><b>NIK:</b> {{ $submission->worker_nik }}</p>
<p><b>Nama:</b> {{ $submission->worker_name }}</p>
<hr>

@foreach ($submission->answers as $a)
    <p>
        <b>{{ $a->question->question }}</b><br>
        {{ $a->answer }}
    </p>
    <hr>
@endforeach
