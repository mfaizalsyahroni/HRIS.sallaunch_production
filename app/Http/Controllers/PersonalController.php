<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\PersonalInfo;
use App\Models\Worker;
use Illuminate\Support\Facades\Hash;

class PersonalController extends Controller
{
    // Form login / verify
    public function verifyForm()
    {
        return view('personal.verify');
    }

    public function verifyWorker(Request $request)
    {
        $request->validate([
            'employee_id' => 'required',
            'password' => 'required'
        ]);

        // Admin IT HRIS
        if ($request->employee_id == '110' && $request->password == 'pw7') {
            session(['verified_worker' => 'ADMIN_IT_HRIS']);
            return redirect()->route('personal.admin.dashboard');
        }

        $worker = Worker::where('employee_id', $request->employee_id)->first();
        if (!$worker || !Hash::check($request->password, $worker->password)) {
            return back()->withErrors(['message' => 'Employee ID or Password is wrong.']);
        }

        session(['verified_worker' => $worker->id]);

        return redirect()->route('personal.list');
    }

    // Check admin
    protected function checkAdmin()
    {
        if (session('verified_worker') !== 'ADMIN_IT_HRIS')
            abort(403, 'Akses ditolak.');
    }

    // Dashboard admin (CRUD)
    public function index()
    {
        $this->checkAdmin();
        $personalInfos = PersonalInfo::with('worker')->latest()->paginate(20);
        $workers = Worker::select('employee_id', 'fullname')->get();
        return view('personal.admin.dashboard', compact('personalInfos', 'workers'));
    }


    // Store (Tambah data + foto)
    public function store(Request $request)
    {
        $this->checkAdmin();

        $request->validate([
            'employee_id' => 'required',
            'fullname' => 'required|string|max:255'
        ]);

        $personalData = $request->all();

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('personal', 'public'); // <-- simpan ke folder 'personal'
            $personalData['photo'] = $path;
        }


        PersonalInfo::create($personalData);

        return redirect()->route('personal.admin.dashboard')
            ->with('message1', 'Data has been successfully added ✅');
    }

    // Update (Edit data + ganti foto)
    public function update(Request $request, $id)
    {
        $personal = PersonalInfo::findOrFail($id);
        

        // VALIDASI WAJIB (minimal)
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'email_personal' => 'nullable|email',
        ]);

        // UPDATE DATA
        $personal->update($validated + [
            'nickname' => $request->nickname,
            'gender' => $request->gender,
            'birth_place' => $request->birth_place,
            'birth_date' => $request->birth_date,
            'marital_status' => $request->marital_status,
            'nationality' => $request->nationality,
            'religion' => $request->religion,
            'nik' => $request->nik,
            'kk_number' => $request->kk_number,
            'passport_number' => $request->passport_number,
            'npwp' => $request->npwp,
            'bpjs_health' => $request->bpjs_health,
            'bpjs_employment' => $request->bpjs_employment,
            'address_current' => $request->address_current,
            'address_ktp' => $request->address_ktp,
            'postal_code' => $request->postal_code,
            'phone' => $request->phone,
            'emergency_contact_name' => $request->emergency_contact_name,
            'phone_emergency' => $request->phone_emergency,
            'emergency_contact_relation' => $request->emergency_contact_relation,
            'join_date' => $request->join_date,
            'employment_status' => $request->employment_status,
            'department' => $request->department,
            'role' => $request->role,
            'blood_type' => $request->blood_type,
            'shirt_size' => $request->shirt_size,
            'notes' => $request->notes,
        ]);

        // PHOTO HANDLING
        if ($request->hasFile('photo')) {
            $file = $request->file('photo')->store('photos', 'public');
            $personal->photo = $file;
            $personal->save();
        }

        return back()->with('message2', 'Data has been successfully updated!🔁');
    }


    // Delete
    public function destroy($id)
    {
        $this->checkAdmin();
        PersonalInfo::findOrFail($id)->delete();
        return back()->with('message', 'Data has been successfully deleted ❌');
    }

    // Worker list (Read-only)
    public function allList()
    {
        // Ambil ID worker yang sudah login dari session
        $workerId = session('verified_worker');

        // Jika session kosong atau admin, abort akses
        if (!$workerId || $workerId === 'ADMIN_IT_HRIS') {
            abort(403, 'Akses ditolak.');
        }

        // Ambil data worker berdasarkan ID
        $worker = Worker::find($workerId);

        if (!$worker) {
            abort(403, 'Worker tidak ditemukan');
        }

        // Ambil data personal info yang sesuai employee_id worker
        $personalInfos = PersonalInfo::where('employee_id', $worker->employee_id)->get();

        // Kirim data ke view list.blade.php
        return view('personal.list', compact('personalInfos'));
    }



}
