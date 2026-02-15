@php
    // Alias untuk kompatibilitas: Jika data dikirim sebagai $data (dari PdfController) atau $leave (dari LeaveController)
    $leave = $data ?? $leave;
@endphp

<!DOCTYPE html>
<html>
<head>
    <title>Surat Cuti - {{ $leave->employee_id }}</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 20px; 
            line-height: 1.6; 
            font-size: 12px; /* Ukuran font lebih kecil untuk PDF agar muat */
        }
        .container { 
            max-width: 800px; 
            margin: 0 auto; 
            border: 1px solid #ddd; 
            padding: 20px; 
            background-color: #f9f9f9; /* Latar belakang abu-abu muda untuk kontras */
        }
        .wrapper { text-align: left; }
        .title { 
            font-size: 24px; 
            font-weight: bold; 
            margin-bottom: 20px; 
            text-align: center; 
            color: #333; /* Warna gelap untuk judul */
        }
        p { 
            margin: 10px 0; 
            font-size: 14px; 
        }
        strong { 
            font-weight: bold; 
            color: #000; /* Warna hitam untuk label */
        }
        .footer { 
            margin-top: 40px; 
            text-align: center; 
            font-size: 10px; 
            color: #666; 
            border-top: 1px solid #ddd; 
            padding-top: 10px; 
        }
        /* Hapus styling button karena tidak perlu di PDF */
    </style>
</head>
<body>
    <div class="container">
        <div class="wrapper">
            <div class="title">Surat Permohonan Cuti</div>
            
            {{-- Data utama dari model Leave --}}
            <p><strong>Employee ID:</strong> {{ $leave->employee_id }}</p>
            <p><strong>Leave Type:</strong> {{ $leave->leave_types }}</p>
            <p><strong>Start Date:</strong> {{ $leave->start_date }}</p> {{-- Format otomatis d-m-Y via accessor --}}
            <p><strong>End Date:</strong> {{ $leave->end_date }}</p> {{-- Format otomatis d-m-Y via accessor --}}
            <p><strong>Reason:</strong> {{ $leave->leave_reason }}</p>
            <p><strong>Status:</strong> {{ $leave->status }}</p>
            
            {{-- Tambahan: Data dari relasi worker (jika ada) --}}
            @if($leave->worker)
                <p><strong>Worker Name:</strong> {{ $leave->worker->name ?? 'N/A' }}</p> {{-- Asumsikan field 'name' ada di Worker --}}
                <p><strong>Worker Department:</strong> {{ $leave->worker->department ?? 'N/A' }}</p> {{-- Tambah jika ada field lain --}}
            @endif
            
            {{-- Opsional: Tambahkan tanda tangan atau catatan --}}
            <div class="footer">
                <p>Dicetak pada: {{ now()->format('d-m-Y H:i:s') }}</p>
                <p>Surat ini sah jika status approved. Hubungi HR untuk konfirmasi.</p>
            </div>
        </div>
    </div>
</body>
</html>