@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/attendance.css') }}">

    <div class="absen">
        <div class="container attendance-container">
            <center>
                @if (Auth::check())
                    <h1>Attendance</h1>

                    <div>
                        <h3>Welcome {{ Auth::user()->fullname }}</h3>
                    </div>

                    {{-- @if (session('message1'))
                        <div>
                            <h3>{{ session('message1') }}</h3>
                        </div>
                    @endif --}} 
                    @if (session('message'))
                        <div id="flashMessage">
                            <h3>{{ session('message') }}</h3>
                        </div>

                        <script>
                            setTimeout(() => {
                                document.getElementById("flashMessage").style.display = "none";
                            }, 8000);
                        </script>
                @endif





                <h1>
                    <table class="visual">
                        <tr style="background-color: #4c8eaf; color: rgb(255, 255, 255);">
                            <th>Date</th>
                            <th>Clock In</th>
                            <th>Clock Out</th>
                            <th>Perfomance</th>
                        </tr>
                        @if (!empty($attendances) && $attendances->count())
                            @foreach ($attendances as $attendance)
                                <tr>
                                    <td>{{ $attendance->work_date }}</td>
                                    <td>{{ $attendance->clock_in_time ?? '' }}</td>
                                    <td>{{ $attendance->clock_out_time ?? '' }}</td>
                                    <td>
                                        @if ($attendance->clock_in_time && $attendance->clock_out_time)
                                            ✅ <!-- Checkmark for completed attendance -->
                                        @else
                                            ✔️ <!-- Cross mark for incomplete attendance -->
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3">No attendance records found for today</td>
                            </tr>
                        @endif
                    </table>


                    <div style="display: flex; justify-content: center; gap: 10px;">
                        <form action="{{ route('attendance.clockin') }}" method="POST">
                            @csrf
                            <button type="submit">
                                <h1>Clock In</h1>
                            </button>
                        </form>


                        <form action="{{ route('attendance.clockout') }}" method="POST">
                            @csrf
                            <button type="submit">
                                <h1>Clock Out</h1>
                            </button>
                        </form>
                    </div>


                    <form action="{{ route('attendance.logout') }}" method="POST">
                        @csrf
                        <button type="submit">
                            <h1>Logout</h1>
                        </button>
                    </form>
                </h1>
            </center>
        </div>

        <div class="pos">
            <div class="card-container">
                <div class="cuaca">
                    <div id="id2ba02c941ef3d"
                        a='{"t":"a","v":"1.2","lang":"id","locs":[],"ssot":"c","sics":"ds","cbkg":"#FFFFFF","cfnt":"#000000","cprb":"#1976D2","cprf":"#FFFFFF"}'>
                        <a href="https://cuacalab.id/widget/">Weather Widget for Website</a> by cuacalab.id
                    </div>
                    <script async src="https://static1.cuacalab.id/widgetjs/?id=id2ba02c941ef3d" frameborder="0" width="200" height="200">
                    </script>
                </div>
            </div>
            <div class="card-container">
                <div class="salat">
                    <p>
                        Jadwal Shalat
                        <iframe src="https://adzan.tafsirweb.com/ajax.row.php?id=83" frameborder="0" width="220"
                            height="220"></iframe>
                    </p>
                </div>
            </div>
        </div>
    </div>

    @endif

@endsection
