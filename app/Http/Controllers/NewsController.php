<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\News;
use App\Models\Worker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    //button onvlick news
    public function verifyForm()
    {
        return view('news.verify');
    }

    //filtering adminITH or worker
    public function verifyWorker(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|integer',
            'password' => 'required|string',
        ]);

        if ($request->employee_id == '110' && $request->password == 'pw7') {
            session(['verified_worker' => 'ADMIN_IT_HRIS']);
            return redirect()->route('news.admin.dashboard');
        }

        $worker = Worker::where('employee_id', $request->employee_id)->first();
        if (!$worker || !Hash::check($request->password, $worker->password)) {
            return back()->withErrors(['message' => 'Employee ID and Your Password is wrong.']);
        }

        session(['verified_worker' => $worker->id]);
        return redirect()->route('news.list');
    }


    public function adminDashboard()
    {
        $this->checkAdmin();
        // $get data form News Model sorted by newest (latest()), and divided into pages with 10 items per page (paginate(10)).
        $news = News::latest()->paginate(10);
        return view('news.admin.dashboard', compact('news'));
    }

    public function create()
    {
        $this->checkAdmin();
        // <a href="{{ route('news.admin.create') }}" class="btn btn-primary mb-3">
        return view('news.admin.create');
    }

    public function store(Request $request)
    {
        $this->checkAdmin();
        $this->saveNews($request);

        // create
        // <form method="POST" action="{{ route('news.admin.store') }}">
        return redirect()->route('news.admin.dashboard')
            ->with('message1', 'News successfully added ✅');
    }

    public function edit($id)
    {
        $this->checkAdmin();
        // Retrieve the existing news record using its ID. If not found, throw a 404 error.
        $news = News::findOrFail($id);


        // <a href="{{ route('news.admin.edit', $item->id) }}">
        // This view displays the edit form, pre-filled with the existing news data.
        return view('news.admin.edit', compact('news'));
    }

    public function update(Request $request, $id)
    {
        $this->checkAdmin();

        // 1. Fetch the existing record to be updated.
        $news = News::findOrFail($id);
        // 2. Update the record with new data from the form and save changes to the database.
        $this->saveNews($request, $news);

        // save as fitur edit
        // <form method="POST" action="{{ route('news.admin.update', $news->id) }}">
        return redirect()->route('news.admin.dashboard')
            ->with('message1', 'Berita berhasil diupdate ✅');
    }

    public function destroy($id)
    {
        $this->checkAdmin();
        $news = News::findOrFail($id);

        // Triggered by the DELETE button/form.

        // Remove the associated thumbnail file from storage.

        if ($news->thumbnail && file_exists(storage_path('app/public/' . $news->thumbnail))) {
            unlink(storage_path('app/public/' . $news->thumbnail));
        }

        // Delete the database record.
        $news->delete();

        return back()->with('message1', 'Berita berhasil dihapus ✅');
    }

    protected function checkAdmin()
    {
        // Restricts access: Checks for the specific admin role in the session.
        if (session('verified_worker') !== 'ADMIN_IT_HRIS') {
            // If it doesn't match, stop with a 403 error.
            abort(403, 'Akses ditolak.');
        }
    }

    protected function saveNews(Request $request, ?News $news = null)
    {
        // This function handles saving the data, for both new news (Create) and existing news (Update).


        // 1. Check all inputs from the form against defined rules (Validation).
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|string',
            'content' => 'required',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'slug' => 'nullable|string|unique:news,slug' . ($news ? ',' . $news->id : ''),
        ]);


        // 2. Initialize the model object: Use the existing $news data, or create a new News object.
        $news = $news ?? new News;

        $news->title = $request->title;
        $news->category = $request->category;
        $news->content = $request->content;
        $news->slug = $request->slug ?: Str::slug($request->title);


        // 3. Manage the thumbnail image: Delete the old image if a new one is uploaded, then store the new one.
        if ($request->hasFile('thumbnail')) {
            if ($news->thumbnail && file_exists(storage_path('app/public/' . $news->thumbnail))) {
                unlink(storage_path('app/public/' . $news->thumbnail));
            }
            $news->thumbnail = $request->file('thumbnail')->store('news', 'public');
        }


        // 4. Set default values (like views count or author) ONLY if this is a NEW record being created.
        if (!$news->exists) {
            $news->views = 0;
            $news->author_id = session('verified_worker') === 'ADMIN_IT_HRIS' ? null : session('verified_worker');
        }


        // 5. Save all the data to the database (INSERT or UPDATE).
        $news->save();
    }


    public function allNewsList()
    {
        // Fetches all news, ordered by creation date (latest first), and paginates them (12 per page).
        $news = News::latest()->paginate(12);
        return view('news.list', compact('news'));
    }

    public function logout(Request $request)
    {
        $request->session()->forget('verified_worker'); 

        // Optionally, you can invalidate the session
        $request->session()->invalidate();

        // Optionally, regenerate the session token
        $request->session()->regenerateToken();

        // Redirect the user to a specific page after logout
        return redirect()->route('home')->with('message', 'You have been logged out successfully.');
    }






}
