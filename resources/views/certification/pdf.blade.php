<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: DejaVu Sans;
            text-align: center;
            padding: 50px;
        }

        .box {
            border: 8px solid #222;
            padding: 40px;
        }

        h1 {
            font-size: 40px;
            margin-bottom: 10px;
        }

        h2 {
            font-size: 24px;
            margin-top: 0;
        }

        .name {
            font-size: 32px;
            font-weight: bold;
        }

        .module {
            font-size: 22px;
            margin: 20px 0;
        }

        .footer {
            margin-top: 50px;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="box">
        <h1>CERTIFICATE OF COMPLETION</h1>
        <h2>This certifies that</h2>

        <div class="name">{{ $cert->worker->fullname }}</div>

        <p>Employee ID: {{ $cert->employee_id }}</p>

        <div class="module">
            has successfully passed<br>
            <strong>{{ $cert->module->module_name }}</strong>
        </div>

        <p>Grade: <strong>{{ $cert->score }}</strong></p>

        <div class="footer">
            Issued on {{ $cert->created_at->format('d F Y') }} <br>
            HRIS Learning System
        </div>
    </div>
</body>

</html>
