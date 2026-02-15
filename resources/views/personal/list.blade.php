@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/personal_admin.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <div class="container text-center mt-4 mb-2">
        <h2>Personal Info</h2>

        @if ($personalInfos->isEmpty())
            <div class="alert alert-warning">Data personal info tidak ditemukan.</div>
        @endif

        <div class="row justify-content-center g-4">
            @foreach ($personalInfos as $personal)
                <div class="col-lg-6 col-md-8">
                    <div class="card h-100 shadow-lg">
                        <div class="card-body text-center">

                            @if ($personal->photo)
                                <img src="{{ asset('storage/' . $personal->photo) }}" class="rounded-circle mb-3"
                                    style="width:180px;height:180px;object-fit:cover;">
                            @else
                                <div class="text-muted mb-3">No Photo</div>
                            @endif


                            {{-- Data Utama --}}
                            <h5 class="card-title">{{ $personal->fullname }}</h5>
                            <p class="mb-1"><b>Employee ID:</b> {{ $personal->employee_id }}</p>
                            <p class="mb-1"><b>Role:</b> {{ $personal->role}}</p>
                            <p class="mb-1"><b>Gender:</b> {{ $personal->gender }}</p>

                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse"
                                data-bs-target="#detail-{{ $personal->id }}">
                                View Details
                            </button>

                            <div class="collapse mt-3" id="detail-{{ $personal->id }}">
                                <table class="table table-sm table-bordered text-start w-100">
                                    @php
                                        $fields = [
                                            'nickname' => 'Nickname',
                                            'birth_place' => 'Birth Place',
                                            'birth_date' => 'Birth Date',
                                            'marital_status' => 'Marital Status',
                                            'nationality' => 'Nationality',
                                            'religion' => 'Religion',
                                            'nik' => 'NIK',
                                            'kk_number' => 'KK Number',
                                            'passport_number' => 'Passport Number',
                                            'npwp' => 'NPWP',
                                            'bpjs_health' => 'BPJS Health',
                                            'bpjs_employment' => 'BPJS Employment',
                                            'address_current' => 'Current Address',
                                            'address_ktp' => 'KTP Address',
                                            'postal_code' => 'Postal Code',
                                            'phone' => 'Phone',
                                            'phone_emergency' => 'Emergency Phone',
                                            'email_personal' => 'Email',
                                            'emergency_contact_name' => 'Emergency Name',
                                            'emergency_contact_relation' => 'Emergency Relation',
                                            'join_date' => 'Join Date',
                                            'employment_status' => 'Employment Status',
                                            'department' => 'Department',
                                            'role' => 'Role',
                                            'blood_type' => 'Blood Type',
                                            'shirt_size' => 'Shirt Size',
                                            'notes' => 'Notes',
                                        ];
                                    @endphp

                                    @foreach ($fields as $key => $label)
                                        @if (!empty($personal->$key))
                                            <tr>
                                                <th style="width: 35%">{{ $label }}</th>
                                                <td>{{ $personal->$key }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
