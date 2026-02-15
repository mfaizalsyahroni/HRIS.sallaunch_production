@extends('layouts.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" integrity="sha512-S...HASH..." crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<h2>🏆 Ranking</h2>

@foreach($ranking as $index => $item)
    <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
        <h3>
            #{{ $index + 1 }} - {{ $item['idea']->title }}
        </h3>
        <p>Final Score: {{ $item['score'] }}</p>
        <p>Votes: {{ $item['vote_count'] }}</p>
    </div>
@endforeach

@if($winner)
    <h2>🏆 WINNER: {{ $winner['idea']->title }}</h2>
@endif

@endsection