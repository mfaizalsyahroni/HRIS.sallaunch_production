{{-- @extends('layouts.app')

@section('content')
    <h3>Hello, {{ Auth::user()->fullname }}</h3>
    <p>Employee Status: {{ Auth::user()->role }}</p>

    @if(session('message'))
        <div>{{ session('message') }}</div>
    @endif

    <a href="{{ route('absensi.index') }}">View Attendance</a>
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit">Logout</button>
    </form>
@endsection --}}
