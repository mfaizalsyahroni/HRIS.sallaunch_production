<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Worker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AuthController extends Controller
{
    //Show the login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    //Handle login request

    public function login(Request $request)
    {
        $request->validate([
            'employee_id' => 'required',
            'password' => 'required|min:2',
        ]);

        // Find worker by employee_id
        $worker = Worker::where('employee_id', $request->employee_id)->first();

        // if employee Not Found or wrong pw
        if (!$worker || !Hash::check($request->password, $worker->password)) {
            return back()
                ->with('message', 'Employee ID or password is incorrect')
                ->withInput($request->only('employee_id'));
        }

        // Login success
        Auth::login($worker);

        return redirect()
            ->route('attendance.index')
            ->with('message', 'Login successful');
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout(); // Log out the authenticated user

        // Optionally, you can invalidate the session
        $request->session()->invalidate();

        // Optionally, regenerate the session token
        $request->session()->regenerateToken();

        // Redirect the user to a specific page after logout
        return redirect()->route('home')->with('message', 'You have been logged out successfully.');
    }
}
