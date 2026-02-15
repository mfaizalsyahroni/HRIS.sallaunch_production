<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Worker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
    // Form verifikasi worker/admin
    public function verifyForm()
    {
        return view('company.verify');
    }

    // Filter admin atau worker
    public function verifyWorker(Request $request)
    {
        $request->validate([
            'employee_id' => 'required',
            'password' => 'required',
        ]);

        // Admin IT HRIS
        if ($request->employee_id == '110' && $request->password == 'pw7') {
            session(['verified_worker' => 'ADMIN_IT_HRIS']);
            return redirect()->route('company.admin.dashboard');
        }

        $worker = Worker::where('employee_id', $request->employee_id)->first();


        if (!$worker || !Hash::check($request->password, $worker->password)) {
            return back()->withErrors(['message' => 'Employee ID or Password is wrong.']);
        }//if worker not found || {or} password not suitable redirect page with error message  

        session(['verified_worker' => $worker->id]);
        //saved worker ID that has been successfully verified

        return redirect()->route('company.list');
    }

    // Admin dashboard
    public function adminDashboard()
    {
        $this->checkAdmin();

        // AMBIL SEMUA PERUSAHAAN
        $companies = Company::all();

        return view('company.admin.dashboard', compact('companies'));
    }


    // Tampilkan form create company
    public function create()
    {
        $this->checkAdmin();
        return view('company.admin.create');
    }

    // Store company baru
    public function store(Request $request)
    {
        $this->checkAdmin();
        $this->saveCompany($request);

        return redirect()->route('company.admin.dashboard')
            ->with('message1', 'Company berhasil ditambahkan ✅');
    }

    // Tampilkan form edit company
    public function edit($id)
    {
        $this->checkAdmin();
        $company = Company::findOrFail($id);

        return view('company.admin.edit', compact('company'));
    }

    // Update company
    public function update(Request $request, $id)
    {
        $this->checkAdmin();
        $company = Company::findOrFail($id);
        $this->saveCompany($request, $company);

        return redirect()->route('company.admin.dashboard')
            ->with('message1', 'Company berhasil diupdate ✅');
    }

    // Delete company
    public function destroy($id)
    {
        $this->checkAdmin();
        $company = Company::findOrFail($id);

        if ($company->logo && file_exists(storage_path('app/public/' . $company->logo))) {
            unlink(storage_path('app/public/' . $company->logo));
        }

        $company->delete();

        return back()->with('message1', 'Company berhasil dihapus ✅');
    }

    // Update stock / KPI
    public function updateStock(Request $request)
    {
        $this->checkAdmin();
        $company = Company::first(); // Asumsi update 1 company saja

        $request->validate([
            'employee_count' => 'required|integer|min:0',
            'department_count' => 'required|integer|min:0',
            'branch_count' => 'required|integer|min:0',
            'project_count' => 'required|integer|min:0',
            'stock_value' => 'required|numeric|min:0',
            'stock_growth' => 'required|numeric',
        ]);

        $company->employee_count = $request->employee_count;
        $company->department_count = $request->department_count;
        $company->branch_count = $request->branch_count;
        $company->project_count = $request->project_count;
        $company->stock_value = $request->stock_value;
        $company->stock_growth = $request->stock_growth;

        $company->save();

        return redirect()->back()->with('message1', 'Company stock berhasil diperbarui ✅');
    }

    // Helper: Simpan company (create atau update)
    protected function saveCompany(Request $request, ?Company $company = null)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'vision' => 'nullable|string',
            'mission' => 'nullable|string',
            'established_at' => 'nullable|date',
            'logo' => 'nullable|image|max:2048',
        ]);

        $company = $company ?? new Company;

        $company->name = $request->name;
        $company->description = $request->description;
        $company->vision = $request->vision;
        $company->mission = $request->mission;
        $company->established_at = $request->established_at;

        if ($request->hasFile('logo')) {
            if ($company->logo && file_exists(storage_path('app/public/' . $company->logo))) {
                unlink(storage_path('app/public/' . $company->logo));
            }
            $company->logo = $request->file('logo')->store('company', 'public');
        }

        $company->save();
    }

    // Check admin
    protected function checkAdmin()
    {
        if (session('verified_worker') !== 'ADMIN_IT_HRIS') {
            abort(403, 'Akses ditolak.');
        }
    }

    // Logout
    public function companyLogout()
    {
        return redirect()->route('home');
    }


    public function storeInstant()
    {
        $this->checkAdmin();

        Company::create([
            'name' => 'New Company',
            'description' => '',
            'vision' => '',
            'mission' => '',
            'employee_count' => 0,
            'department_count' => 0,
            'branch_count' => 0,
            'project_count' => 0,
            'stock_value' => 0,
            'stock_growth' => 0,
            'established_at' => now(),
        ]);

        return redirect()->route('company.admin.dashboard')
            ->with('message1', 'Perusahaan baru berhasil dibuat!');
    }

    public function allList()
    {
        $companies = Company::latest()->paginate(12);
        return view('company.list', compact('companies'));;
    }

}