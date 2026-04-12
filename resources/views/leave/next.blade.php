@extends('layouts.app')

@section('content')
    <!-- jQuery & jQuery UI -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    <link rel="stylesheet" href="{{ asset('css/feature_leavess.css') }}">

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

            {{-- Info sisa cuti --}}
            @if ($worker)
                <div
                    class="leave-balance-card {{ $worker->leave_balance < 12 ? 'has-taken' : '' }} {{ $worker->leave_balance <= 3 ? 'low-balance' : '' }}">
                    <div class="balance-info">
                        <span class="label">Remaining Leave Balance</span>
                        <div class="value-container">
                            <span class="current-value">{{ $worker->leave_balance }}</span>
                            <span class="separator">/ 12 Days</span>
                        </div>
                    </div>
                    <div class="balance-progress">
                        @php $percentage = ($worker->leave_balance / 12) * 100; @endphp
                        <div class="progress-bar" style="width: {{ $percentage }}%"></div>
                    </div>
                </div>
            @endif


            <form action="{{ route('leave.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="leave_type">Leave Types</label>
                    <select name="leave_type" id="leave_type" required class="input-login">
                        <option value="Annual Leave">Annual Leave</option>
                        <option value="Sick Leave">Sick Leave</option>
                        <option value="Maternity Leave">Maternity Leave</option>
                        <option value="Menstrual Leave">Menstrual Leave</option>
                        <option value="Study Leave">Study Leave</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="start_date">Start Date</label>
                    <input type="text" name="start_date" id="start_date" required class="input-login" autocomplete="off"
                        placeholder="dd/mm/yyyy">
                </div>

                <div class="form-group">
                    <label for="end_date">End Date</label>
                    <input type="text" name="end_date" id="end_date" required class="input-login" autocomplete="off"
                        placeholder="dd/mm/yyyy">
                </div>

                {{-- Preview total hari --}}
                <div class="form-group">
                    <label>Leave Duration</label>
                    <input type="text" id="total_days_preview" class="input-login" readonly
                        placeholder="Automatically calculated" style="background:#f0f0f0; cursor:not-allowed;">
                </div>

                <div class="form-group">
                    <label for="leave_reason">Leave Reason</label>
                    <input type="text" name="leave_reason" id="reason-leave" required class="input-login"
                        autocomplete="off" placeholder="Enter the reason leave">
                </div>

                <button type="submit" class="button-login">Submit</button>
            </form>
        </div>
    </div>

    {{-- Update script --}}
    <script>
        $(function() {
            $("#start_date, #end_date").datepicker({
                dateFormat: "dd-mm-yy"
            });

            function hitungHari() {
                const start = $("#start_date").datepicker("getDate");
                const end = $("#end_date").datepicker("getDate");
                if (start && end && end >= start) {
                    const diff = Math.round((end - start) / (1000 * 60 * 60 * 24)) + 1;
                    $("#total_days_preview").val(diff + " days");
                } else {
                    $("#total_days_preview").val("");
                }
            }

            $("#start_date, #end_date").on("change", function() {
                const start = $("#start_date").datepicker("getDate");
                const end = $("#end_date").datepicker("getDate");
                if (end && start && end < start) {
                    alert("Tanggal akhir tidak boleh sebelum tanggal mulai.");
                    $("#end_date").val("");
                }
                hitungHari();
            });
        });
    </script>
@endsection
