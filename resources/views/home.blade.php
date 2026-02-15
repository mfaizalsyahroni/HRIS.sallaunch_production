<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="{{ asset('css/style_home.css') }}">
    <title>Home</title>
</head>

<body>

    @include('headerhome')

    <div class="section">
        <h4><u>Employee Intranet </u></h4>

        <div class="btn-group">
            {{-- 1 --}}
            <button class="home-btn" onclick="window.location.href='{{ route('cctv.verifyForm') }}'">
                <img src="img/home/cctv.jpeg" width="30px">
                <div class="mb">
                    CCTV Access
                </div>
            </button>

            {{-- 2 --}}
            <button class="home-btn" onclick="window.location.href='{{ route('personal.verifyForm') }}'">
                <img src="img/home/pribadi.png" width="30px">
                <div class="mb">
                    Personal Info
                </div>
            </button>

            {{-- 3 --}}
            <button class="home-btn" onclick="window.location.href='{{ route('company.verifyForm') }}'">
                <img src="img/home/kantor.png" width="30px">
                <div class="mb">
                    Company Information
                </div>
            </button>

            {{-- 4 --}}
            <button class="home-btn" onclick="window.location.href='{{ route('news.verifyForm') }}'">
                <img src="img/home/berita.png" width="30px">
                <div class="mb">
                    News
                </div>
            </button>

            {{-- 5 --}}
            <button class="home-btn" onclick="window.location.href='{{ route('leave.create') }}'">
                <img src="img/home/tanggal.jpg" width="30px">
                <div class="mb">
                    Leave
                </div>
            </button>

            {{-- 6 --}}
            <button class="home-btn" onclick="window.location.href='{{ route('overtime.verify') }}'">
                <img src="img/home/overtime.png" width="30px">
                <div class="mb">
                    Overtime
                </div>
            </button>

            {{-- 7 --}}
            <button class="home-btn" onclick="window.location.href='{{ route('attendance.index') }}'">
                <img src="img/home/absensi.jpeg" width="30px">
                <div class="mb">
                    Attendance
                </div>
            </button>

            {{-- 8 --}}
            <button class="home-btn" onclick="window.location.href='{{ route('new_employee.verify') }}'">
                <img src="img/home/ne.png" width="30px">
                <div class="mb">
                    New Employee
                </div>
            </button>
        </div>
    </div>



    <div class="section">
        <h4><u>Employee Voice</u></h4>

        <div class="btn-group">
            {{-- 9 --}}
            <button class="home-btn" onclick="window.location.href='{{ route('survey.verifyForm') }}'">
                <img src="img/home/survei.png" width="30px">
                <div class="mb">Survey</div>
            </button>


            {{-- 10 --}}
            <button class="home-btn" onclick="window.location.href='{{ route('suggestions.verifyForm') }}'">
                <img src="img/home/saran.png" width="30px">
                <div class="mb">Suggestions</div>
            </button>


            {{-- 11 --}}
            <button class="home-btn" onclick="window.location.href='{{ route('payroll.verify') }}'">
                <img src="img/part/payroll.png" width="30px">
                <div class="mb">
                    Payroll
                </div>
            </button>
        </div>
    </div>


    <div class="section">
        <h4><u>Digital Learning</u></h4>


        <div class="btn-group">
            {{-- 12 --}}
            <button class="home-btn" onclick="window.location.href='{{ route('learningplan.verify') }}'">
                <img src="img/home/rencana.jpg" width="30px">
                <div class="mb">
                    Learning Plan
                </div>
            </button>

            {{-- 13 --}}
            <button class="home-btn" onclick="window.location.href='{{ route('certification.verify') }}'">
                <img src="img/home/sertifikat.png" width="30px">
                <div class="mb">
                    Certification
                </div>
            </button>

            {{-- 14 --}}
            <button class="home-btn" onclick="window.location.href='{{ route('idea.verify') }}'">
                <img src="img/home/idea.png" width="30px">
                <div class="mb">
                    <div class="idea-main">IDEA</div>
                    <div class="idea-sub">Innovation & Development Award</div>
                </div>
            </button>

            {{-- 15 --}}
            <button class="home-btn" onclick="window.location.href='{{ route('achievement.verify') }}'">
                <img src="img/home/achievement.jpg" width="30px">
                <div class="mb">
                    Achievement
                </div>
            </button>

            {{-- 16 --}}
            <button class="home-btn" onclick="window.location.href='/contact'">
                <img src="img/home/peringkat.jpg" width="30px">
                <div class="mb">
                    Leaderboard
                </div>
            </button>

            {{-- 17 --}}
            <button class="home-btn" onclick="window.location.href='/contact'">
                <img src="img/home/hp.jpg" width="30px">
                <div class="mb">
                    Online Training
                </div>
            </button>
        </div>
    </div>

    @include('footerhome')

</body>

</html>
