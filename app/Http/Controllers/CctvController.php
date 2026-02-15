<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cctv;
use App\Models\Worker;
use Illuminate\Support\Facades\Hash;

class CctvController extends Controller
{
    // FORM LOGIN CCTV
    public function verifyForm()
    {
        return view('cctv.verify');
    }

    // LOGIC LOGIN CCTV
    public function verifyWorker(Request $request)
    {
        $request->validate([
            'employee_id' => 'required',
            'password' => 'required'
        ]);

        // ADMIN CCTV
        if ($request->employee_id == '110' && $request->password == 'pw7') {
            session(['verified_worker' => 'ADMIN_IT_HRIS']);
            return redirect()->route('cctv.admin.dashboard');
        }

        // WORKER NORMAL
        $worker = Worker::where('employee_id', $request->employee_id)->first();

        if (!$worker || !Hash::check($request->password, $worker->password)) {
            return back()->withErrors(['message' => 'Employee ID or Password is wrong.']);
        }

        session(['verified_worker' => $worker->id]);

        return redirect()->route('cctv.list');
    }

    // CEK ADMIN
    protected function checkAdmin()
    {
        if (session('verified_worker') !== 'ADMIN_IT_HRIS')
            abort(403, 'Akses ditolak.');
    }

    // DASHBOARD ADMIN CCTV
    public function adminDashboard()
    {
        $this->checkAdmin();

        $cctvs = Cctv::latest()->paginate(20);

        return view('cctv.admin.dashboard', compact('cctvs'));
    }

    // CREATE CCTV
    public function store(Request $request)
    {
        $this->checkAdmin();

        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'source' => 'required|string',
            'type' => 'nullable|string|max:100',
            'online' => 'nullable|boolean',
            'notes' => 'nullable|string'
        ]);

        Cctv::create([
            'name' => $request->name,
            'location' => $request->location,
            'source' => $request->source,
            'type' => $request->type,
            'online' => $request->online ?? true,
            'notes' => $request->notes,
        ]);

        return back()->with('message', 'CCTV added successfully');
    }


    // UPDATE CCTV
    public function update(Request $request, $id)
    {
        $this->checkAdmin();

        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'source' => 'required|string',
            'type' => 'nullable|string|max:100',
            'online' => 'nullable|boolean',
            'notes' => 'nullable|string'
        ]);

        $cctv = Cctv::findOrFail($id);

        $cctv->update([
            'name' => $request->name,
            'location' => $request->location,
            'source' => $request->source,
            'type' => $request->type,
            'online' => $request->online ?? $cctv->online,
            'notes' => $request->notes,
        ]);

        return back()->with('message', 'CCTV updated successfully');
    }


    // DELETE CCTV
    public function destroy($id)
    {
        $this->checkAdmin();

        Cctv::findOrFail($id)->delete();

        return back()->with('message', 'CCTV deleted successfully');
    }

    // VIEW CCTV PUBLIC
    public function publicList()
    {
        // pekerja biasa
        $worker = session('verified_worker');
        if (!$worker || $worker === 'ADMIN_IT_HRIS') {
            abort(403, 'Access denied');
        }

        $cctvs = Cctv::all();

        return view('cctv.list', compact('cctvs'));
    }
}
