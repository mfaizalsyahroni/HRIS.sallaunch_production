@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/personal_admin.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <div class="container mt-4">
        <h2>Personal Dashboard (CRUD Admin)</h2>

        {{-- Flash Message --}}
        @if (session('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif
        @if (session('message1'))
            <div class="alert alert-success">{{ session('message1') }}</div>
        @endif
        @if (session('message2'))
            <div class="alert alert-success">{{ session('message2') }}</div>
        @endif

        {{-- ================= CREATE ================= --}}
        <h4 class="mt-4">Add New Personal Info</h4>
        <form action="{{ route('personal.store') }}" method="POST" enctype="multipart/form-data" class="mb-4">
            @csrf
            <div class="row g-2">
                <div class="col-md-3">
                    <label>Employee ID</label>
                    <select name="employee_id" class="form-control" required>
                        <option value="">-- Select --</option>
                        @foreach ($workers as $worker)
                            <option value="{{ $worker->employee_id }}"> ({{ $worker->employee_id }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label>Photo</label>
                    <input type="file" name="photo" class="form-control">
                </div>

                <div class="col-md-3">
                    <label>Full Name</label><input type="text" name="fullname" class="form-control" required>
                </div>

                <div class="col-md-3">
                    <label>Nickname</label><input type="text" name="nickname" class="form-control">
                </div>

                <div class="col-md-3"><label>Gender</label>
                    <select name="gender" class="form-control">
                        <option value="">-- Select --</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label>Birth Place</label><input type="text" name="birth_place" class="form-control">
                </div>

                <div class="col-md-3">
                    <label>Birth Date</label><input type="date" name="birth_date" class="form-control">
                </div>

                <div class="col-md-3">
                    <label>Marital Status</label><input type="text" name="marital_status" class="form-control">
                </div>

                <div class="col-md-3">
                    <label>Nationality</label><input type="text" name="nationality" class="form-control">
                </div>

                <div class="col-md-3">
                    <label>Religion</label><input type="text" name="religion" class="form-control">
                </div>

                {{-- Dokumen Identitas --}}
                <div class="col-md-3">
                    <label>NIK</label><input type="text" name="nik" class="form-control">
                </div>

                <div class="col-md-3">
                    <label>KK Number</label><input type="text" name="kk_number" class="form-control">
                </div>

                <div class="col-md-3">
                    <label>Passport Number</label><input type="text" name="passport_number" class="form-control">
                </div>

                <div class="col-md-3">
                    <label>NPWP</label><input type="text" name="npwp" class="form-control">
                </div>

                {{-- BPJS --}}
                <div class="col-md-3">
                    <label>BPJS Health</label><input type="text" name="bpjs_health" class="form-control">
                </div>

                <div class="col-md-3">
                    <label>BPJS Employment</label><input type="text" name="bpjs_employment" class="form-control">
                </div>

                {{-- Alamat --}}
                <div class="col-md-6">
                    <label>Current Address</label>
                    <textarea name="address_current" class="form-control"></textarea>
                </div>

                <div class="col-md-6">
                    <label>KTP Address</label>
                    <textarea name="address_ktp" class="form-control"></textarea>
                </div>

                <div class="col-md-3">
                    <label>Postal Code</label><input type="text" name="postal_code" class="form-control">
                </div>

                {{-- Kontak --}}
                <div class="col-md-3">
                    <label>Phone</label><input type="text" name="phone" class="form-control">
                </div>


                <div class="col-md-3">
                    <label>Email Personal</label><input type="email" name="email_personal" class="form-control">
                </div>

                <div class="col-md-3">
                    <label>Emergency Contact Name</label><input type="text" name="emergency_contact_name"
                        class="form-control">
                </div>

                <div class="col-md-3">
                    <label>Emergency Phone</label><input type="text" name="phone_emergency" class="form-control">
                </div>

                <div class="col-md-3">
                    <label>Emergency Contact Relation</label><input type="text" name="emergency_contact_relation"
                        class="form-control">
                </div>

                {{-- Info Pekerjaan --}}
                <div class="col-md-3">
                    <label>Join Date</label><input type="date" name="join_date" class="form-control">
                </div>

                <div class="col-md-3">
                    <label>Employment Status</label><input type="text" name="employment_status" class="form-control">
                </div>

                <div class="col-md-3">
                    <label>Department</label><input type="text" name="department" class="form-control">
                </div>

                <div class="col-md-3">
                    <label>Role</label>
                    <select name="role" class="form-control">
                        <option value="">-- Select --</option>
                        @foreach (\App\Models\Worker::pluck('role')->unique()->sort() as $role)
                            <option value="{{ $role }}"
                                {{ old('role', $personal->role ?? '') == $role ? 'selected' : '' }}>
                                {{ $role }}
                            </option>
                        @endforeach
                    </select>
                </div>



                {{-- Lainnya --}}
                <div class="col-md-3">
                    <label>Blood Type</label>
                    <select name="blood_type" class="form-control">
                        <option value="">-- Select --</option>
                        <option value="O">O</option>
                        <option value="O">A</option>
                        <option value="O">B</option>
                        <option value="O">AB</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label>Shirt Size</label>
                    <select name="shirt_size" class="form-control">
                        <option value="">-- Select --</option>
                        <option value="M">M</option>
                        <option value="L">L</option>
                        <option value="XL">XL</option>
                        <option value="XXL">XXL</option>
                    </select>
                </div>

                <div class="col-md-12">
                    <label>Notes</label>
                    <textarea name="notes" class="form-control"></textarea>
                </div>

            </div>

            <button class="btn btn-primary mt-4">Add Personal Info</button>
        </form>

        {{-- ================= READ ================= --}}
        <h4 class="mt-4">All User Personal Info</h4>
        <div class="row g-3">
            @foreach ($personalInfos as $personal)
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            {{-- Foto --}}
                            @if ($personal->photo)
                                <img src="{{ asset('storage/' . $personal->photo) }}" alt="Photo"
                                    class="rounded-circle mb-3" style="width:200px;height:200px;object-fit:cover;">
                            @else
                                <div class="text-muted mb-3">No Photo</div>
                            @endif

                            {{-- Data Utama --}}
                            <h5 class="card-title">{{ $personal->fullname }}</h5>
                            <p class="card-text mb-1"><b>Employee ID:</b> {{ $personal->employee_id }}</p>
                            <p class="card-text mb-1"><b>Role:</b> {{ $personal->role }}</p>
                            <p class="card-text"><b>Gender:</b> {{ $personal->gender }}</p>


                            {{-- Tombol Detail Collapsible --}}
                            <button class="btn btn-sm btn-outline-primary" type="button" data-bs-toggle="collapse"
                                data-bs-target="#detail-{{ $personal->id }}">
                                View Full Details
                            </button>


                            <div class="d-flex justify-content-center gap-2 mt-2">
                                {{-- Tombol Edit --}}
                                <button class="btn btn-sm btn-warning px-3" data-bs-toggle="modal"
                                    data-bs-target="#editModal-{{ $personal->id }}">
                                    Edit
                                </button>

                                {{-- Tombol Delete --}}
                                <form action="{{ route('personal.destroy', $personal->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus data ini?')">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-sm btn-danger px-3">
                                        Delete
                                    </button>
                                </form>
                            </div>



                            {{-- Detail Collapsible --}}
                            <div class="collapse mt-3" id="detail-{{ $personal->id }}">
                                <table class="table table-sm table-bordered text-start">
                                    <tbody>
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
                                                    <th style="width: 40%">{{ $label }}</th>
                                                    <td>{{ $personal->$key }}</td>
                                                </tr>
                                            @endif
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- ================= UPDATE MODAL ================= --}}
                <div class="modal fade" id="editModal-{{ $personal->id }}" tabindex="-1">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h5 class="modal-title">Edit Personal Info ({{ $personal->fullname }})</h5>
                                <button class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <form action="{{ route('personal.update', $personal->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="modal-body">
                                    <div class="row g-2">

                                        {{-- Photo --}}
                                        <div class="col-md-4">
                                            <label>Photo</label>
                                            <input type="file" name="photo" class="form-control">
                                        </div>

                                        {{-- Basic Info --}}
                                        <div class="col-md-4">
                                            <label>Full Name</label>
                                            <input type="text" name="fullname" class="form-control"
                                                value="{{ $personal->fullname }}">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Nickname</label>
                                            <input type="text" name="nickname" class="form-control"
                                                value="{{ $personal->nickname }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Gender</label>
                                            <select name="gender" class="form-control">
                                                <option value="">-- Select --</option>
                                                <option value="Male"
                                                    {{ $personal->gender == 'Male' ? 'selected' : '' }}>Male</option>
                                                <option value="Female"
                                                    {{ $personal->gender == 'Female' ? 'selected' : '' }}>Female</option>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Birth Place</label>
                                            <input type="text" name="birth_place" class="form-control"
                                                value="{{ $personal->birth_place }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Birth Date</label>
                                            <input type="date" name="birth_date" class="form-control"
                                                value="{{ $personal->birth_date }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Marital Status</label>
                                            <input type="text" name="marital_status" class="form-control"
                                                value="{{ $personal->marital_status }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Nationality</label>
                                            <input type="text" name="nationality" class="form-control"
                                                value="{{ $personal->nationality }}">
                                        </div>
                                        <div class="col-md-3">
                                            <label>Religion</label>
                                            <input type="text" name="religion" class="form-control"
                                                value="{{ $personal->religion }}">
                                        </div>

                                        {{-- Identitas --}}
                                        <div class="col-md-3"><label>NIK</label><input type="text" name="nik"
                                                class="form-control" value="{{ $personal->nik }}"></div>
                                        <div class="col-md-3"><label>KK Number</label><input type="text"
                                                name="kk_number" class="form-control"
                                                value="{{ $personal->kk_number }}"></div>
                                        <div class="col-md-3"><label>Passport</label><input type="text"
                                                name="passport_number" class="form-control"
                                                value="{{ $personal->passport_number }}"></div>
                                        <div class="col-md-3"><label>NPWP</label><input type="text" name="npwp"
                                                class="form-control" value="{{ $personal->npwp }}"></div>

                                        {{-- BPJS --}}
                                        <div class="col-md-3"><label>BPJS Health</label><input type="text"
                                                name="bpjs_health" class="form-control"
                                                value="{{ $personal->bpjs_health }}"></div>
                                        <div class="col-md-3"><label>BPJS Employment</label><input type="text"
                                                name="bpjs_employment" class="form-control"
                                                value="{{ $personal->bpjs_employment }}"></div>

                                        {{-- Alamat --}}
                                        <div class="col-md-6">
                                            <label>Current Address</label>
                                            <textarea name="address_current" class="form-control">{{ $personal->address_current }}</textarea>
                                        </div>
                                        <div class="col-md-6">
                                            <label>KTP Address</label>
                                            <textarea name="address_ktp" class="form-control">{{ $personal->address_ktp }}</textarea>
                                        </div>
                                        <div class="col-md-3">
                                            <label>Postal Code</label>
                                            <input type="text" name="postal_code" class="form-control"
                                                value="{{ $personal->postal_code }}">
                                        </div>

                                        {{-- Kontak --}}
                                        <div class="col-md-3"><label>Phone</label><input type="text" name="phone"
                                                class="form-control" value="{{ $personal->phone }}"></div>
                                        <div class="col-md-3"><label>Email</label><input type="email"
                                                name="email_personal" class="form-control"
                                                value="{{ $personal->email_personal }}"></div>
                                        <div class="col-md-3"><label>Emergency Name</label><input type="text"
                                                name="emergency_contact_name" class="form-control"
                                                value="{{ $personal->emergency_contact_name }}"></div>
                                        <div class="col-md-3"><label>Emergency Phone</label><input type="text"
                                                name="phone_emergency" class="form-control"
                                                value="{{ $personal->phone_emergency }}"></div>
                                        <div class="col-md-3"><label>Emergency Relation</label><input type="text"
                                                name="emergency_contact_relation" class="form-control"
                                                value="{{ $personal->emergency_contact_relation }}"></div>

                                        {{-- Job --}}
                                        <div class="col-md-3"><label>Join Date</label><input type="date"
                                                name="join_date" class="form-control"
                                                value="{{ $personal->join_date }}"></div>
                                        <div class="col-md-3"><label>Employment Status</label><input type="text"
                                                name="employment_status" class="form-control"
                                                value="{{ $personal->employment_status }}"></div>
                                        <div class="col-md-3"><label>Department</label><input type="text"
                                                name="department" class="form-control"
                                                value="{{ $personal->department }}"></div>

                                        <div class="col-md-3">
                                            <label>Role</label>
                                            <select name="role" class="form-control">
                                                @foreach (\App\Models\Worker::pluck('role')->unique()->sort() as $role)
                                                    <option value="{{ $role }}"
                                                        {{ $personal->role == $role ? 'selected' : '' }}>
                                                        {{ $role }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        {{-- Others --}}
                                        <div class="col-md-3">
                                            <label>Blood Type</label>
                                            <select name="blood_type" class="form-control">
                                                @foreach (['O', 'A', 'B', 'AB'] as $type)
                                                    <option value="{{ $type }}"
                                                        {{ $personal->blood_type == $type ? 'selected' : '' }}>
                                                        {{ $type }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <label>Shirt Size</label>
                                            <select name="shirt_size" class="form-control">
                                                @foreach (['M', 'L', 'XL', 'XXL'] as $size)
                                                    <option value="{{ $size }}"
                                                        {{ $personal->shirt_size == $size ? 'selected' : '' }}>
                                                        {{ $size }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-12">
                                            <label>Notes</label>
                                            <textarea name="notes" class="form-control">{{ $personal->notes }}</textarea>
                                        </div>

                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancel</button>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-3">
            {{ $personalInfos->links() }}
        </div>
    @endsection
