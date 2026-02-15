@extends('layouts.app')

@section('content')
<!-- jQuery & jQuery UI -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

<link rel="stylesheet" href="{{ asset('css/feature_leave.css') }}">

<div class="container">
    <div class="wrapper">
        <div class="title">Leave Request Form</div>
        <p class="sec-title">Please complete the form below to apply for a leave of absence</p>

        {{-- Notifikasi sukses --}}
        @if (session('message'))
            <div class="notif-succes" id="notification">
                {{ session('message') }}
            </div>
            <script>
                setTimeout(() => {
                    const notif = document.getElementById('notification');
                    if (notif) notif.style.display = 'none';
                }, 8000);
            </script>
        @endif

        {{-- Notifikasi error --}}
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('leave.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="leave_types">Leave Types</label>
                <select name="leave_types" id="leave_types" required class="input-login">
                    <option value="Annual Leave">Annual Leave</option>
                    <option value="Sick Leave">Sick Leave</option>
                    <option value="Maternity Leave">Maternity Leave</option>
                    <option value="Menstrual Leave">Menstrual Leave</option>
                    <option value="Study Leave">Study Leave</option>
                </select>
            </div>

            <div class="form-group">
                <label for="start_date">Start Date</label>
                <input type="text" name="start_date" id="start_date" required class="input-login" autocomplete="off" placeholder="dd/mm/yyyy">
            </div>

            <div class="form-group">
                <label for="end_date">End Date</label>
                <input type="text" name="end_date" id="end_date" required class="input-login" autocomplete="off" placeholder="dd/mm/yyyy">
            </div>

            <div class="form-group">
                <label for="leave_reason">Leave Reason</label>
                <input type="text" name="leave_reason" id="reason-leave" required class="input-login" autocomplete="off" placeholder="Masukan Alasan cuti">
            </div>

            <button type="submit" class="button-login">Submit</button>
        </form>
    </div>
</div>

{{-- Script untuk datepicker & validasi tanggal --}}
<script>
$(function() {
    // Inisialisasi datepicker dengan format dd/mm/yyyy
    $("#start_date, #end_date").datepicker({
        dateFormat: "dd-mm-yy"
    });

    // Validasi: tanggal akhir tidak boleh sebelum tanggal awal
    $("#end_date").change(function() {
        const start = $("#start_date").datepicker("getDate");
        const end = $(this).datepicker("getDate");
        if (end && start && end < start) {
            alert("Tanggal akhir cuti harus setelah atau sama dengan tanggal mulai.");
            $(this).val("");
        }
    });
});
</script>
@endsection

