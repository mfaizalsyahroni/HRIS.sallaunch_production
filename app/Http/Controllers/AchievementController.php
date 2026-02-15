<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Worker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AchievementController extends Controller
{

    public function verify()
    {
        return view('achievement.verify');
    }

    public function verifyWorker(Request $request)
    {
        $request->validate([
            'employee_id' => 'required',
            'password' => 'required',
        ]);

        //Admin IT HRIS
        if ($request->employee_id == 110 && $request->password === 'pw7'){
            session(['verified_worker' => 'ADMIN_IT_HRIS']);
            return redirect()->route('achievement.admin.dashboard');
        }

        //object = find one data in the workers tabel whose employee_id matches the input from the user. And save resultu to object(variabel) $worker
        $worker = Worker::where('employee_id', $request->employee_id)->first();

        if (!$worker || !Hash::check($request->password, $worker->password)) {
            return back()->withErrors(['error' =>'Your Employee ID & Password is wrong']);
        }

        session(['verified_worker' => $worker->id]);     
        
        return redirect()->route('achievement.staff');
    }


    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
