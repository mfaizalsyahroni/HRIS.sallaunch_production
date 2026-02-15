@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/lp_log.css') }}">

    <div class="container">
        <div class="wrapper">
            <h1 class="title">Login Learning Plan</h1>

            <form action="{{ route('learningplan.verifyWorker') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                <label for="employee_id">Employee ID</label>
                <input type="text" name="employee_id" id="employee_id" required class="input-login" autocomplete="off">
                </div>

                <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="employee_id" required class="input-login" autocomplete="off">
                </div>

                <button type="submit" class="button-login">Submit</button>
            </form>
        </div>
    </div>

@endsection