@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/company.css') }}">

<div class="container">
    <div class="wrapper">
        <h1 class="title">Company Access</h1>
        
        @if(session('error'))
            <div class="notif-login">{{ session('error') }}</div>
        @endif

        <form action="{{ route('company.verifyWorker') }}" method="POST" class="form-login">
            @csrf
            
            <div class="form-group">
                <label for="employee_id">Employee ID</label>
                <input type="text" name="employee_id" required class="input-login" autocomplete="off">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" required class="input-login" autocomplete="off">
            </div>

            <button type="submit" class="button-login">Submit</button>
        </form>
    </div>
</div>
@endsection
