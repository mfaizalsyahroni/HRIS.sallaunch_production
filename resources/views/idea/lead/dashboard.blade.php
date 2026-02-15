@extends('layouts.app')


@section('content')
<h2>Lead Dashboard - Review Ideas</h2>

@foreach ($ideas as $idea)
    <div style="border:1px solid #ccc; padding:10px; margin-bottom:20px;">
        <h3>{{ $idea->title }}</h3>
        <p>Votes: {{ $idea->votes_count }}</p>

        <form method="POST" action="{{ route('idea.review', $idea->id) }}">
            @csrf

            <label>Business Impact (1-5)</label>
            <input type="number" name="business_impact" min="1" max="5" required>

            <label>Feasibility (1-5)</label>
            <input type="number" name="feasibility" min="1" max="5" required>

            <label>Sustainability (1-5)</label>
            <input type="number" name="sustainability" min="1" max="5" required>

            <button type="submit">Submit Review</button>
        </form>
    </div>
@endforeach

<a href="{{ route('idea.winner') }}">See Final Ranking</a>


@endsection