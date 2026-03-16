<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Worker;
use App\Models\SalaryGrade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class WorkerController extends Controller
{

    public function verify()
    {
        return view('new_employee.verify');
    }

    public function verifyWorker(Request $request)
    {
        $request->validate([
            'employee_id' => 'required',
            'password' => 'required',
        ]);

        // ✅ WHITELIST (can be create)
        if (
            ($request->employee_id == '100' && $request->password === 'pw7') ||
            ($request->employee_id == '110' && $request->password === 'pw7')
        ) {
            session(['verified_worker' => $request->employee_id]);
            return redirect()->route('new_employee.create');
        }

        // 🔎 check worker from database
        $worker = Worker::where('employee_id', $request->employee_id)->first();

        // ❌ not found
        if (!$worker) {
            return back()->withErrors([
                'employee_id' => 'Employee ID not found.'
            ]);
        }

        // ❌ password is wrong
        if (!Hash::check($request->password, $worker->password)) {
            return back()->withErrors([
                'password' => 'Password is incorrect.'
            ]);
        }

        // ❌ DONE APPLY → STOP
        return back()->with('error', 'Employee already registered. You cannot continue.');
    }

    public function create()
    {
        return view('new_employee.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|numeric|unique:workers,employee_id',
            'fullname' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'employment_type' => 'required|in:permanent,contract,intern,probation,freelance',
            'password' => 'required|min:2|confirmed',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $worker = Worker::create($validated);

        return redirect()
            ->route('new_employee.salary', $worker->employee_id)
            ->with('success', 'Worker created successfully.');
    }

    public function profile(Worker $worker)
    {
        return view('new_employee.profile', compact('worker'));
    }

    public function allemployee()
    {
        $worker = Worker::orderBy('employee_id', 'asc')->get();
        // $worker = Worker::latest()->get(); (off)
        return view('new_employee.allemployee', compact('worker'));
    }



    public function update(Request $request, $id)
    {
        $employee = Worker::findOrFail($id);

        $data = [
            'fullname' => $request->fullname,
            'role' => $request->role,
            'employment_type' => $request->employment_type,
        ];

        // just update if filled
        if ($request->filled('working_period_start')) {
            $data['working_period_start'] = $request->working_period_start;
        }

        $employee->update($data);

        if ($request->filled('password')) {
            $employee->password = bcrypt($request->password);
            $employee->save();
        }

        return redirect()->route('view.allemployee')
            ->with('success', 'Employee updated successfully ✅.');
    }
    public function destroy(Request $request, $employee_id)
    {
        $worker = Worker::where('employee_id', $employee_id)->firstOrFail();
        $worker->delete();

        return back()->with('success', 'Employee deleted successfully ✅.');
    }


    public function salary(Worker $worker)
    {
        return view('new_employee.salary', compact('worker'));
    }

    public function storeSalary(Request $request, Worker $worker)
    {
        $request->validate([
            'position' => 'required|string|max:255',
            'grade_name' => 'required|in:Junior,Middle,Senior',
            'basic_salary' => 'required|numeric|min:0',
            'employment_type' => 'required|in:permanent,contract,intern,probation,freelance',
        ]);

        SalaryGrade::create([
            'position' => $request->position,
            'grade_name' => $request->grade_name,
            'basic_salary' => $request->basic_salary,
            'employment_type' => $request->employment_type,
            'is_active' => $request->has('is_active'),
        ]);

        // Update role worker sesuai position yang baru dibuat
        $worker->update(['role' => $request->position]);

        return redirect()->route('new_employee.profile', $worker->employee_id)
            ->with('success', 'Salary grade created and assigned.');
    }


    public function logout(Request $request)
    {
        $request->session()->forget('verified_worker');

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
    public function credentials()
    {

    }


}
