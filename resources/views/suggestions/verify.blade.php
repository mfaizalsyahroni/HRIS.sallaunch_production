@extends('layouts.app')


@section('content')
    <link rel="stylesheet" href="{{ asset('css/suggestionslog.css') }}">

    <div class="container">
        <div class="wrapper">

            <h1 class="title">Personal Info</h1>
            <form action="{{ route('suggestions.verifyWorker') }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                @csrf

                <div class="form-group">
                    <label for="employee_id">Employee ID</label>
                    <input type="text" name="employee_id" id="employee_id" required class="input-login">
                </div>

                <div>
                    <label for="password">Password</label>
                    <input type="password" name="password" id="password" required class="input-login">
                </div>

                <button type="submit" class="button-login">Submit</button>
            </form>

        </div>
    </div>
@endsection
