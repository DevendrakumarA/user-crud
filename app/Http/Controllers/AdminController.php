<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
 
class AdminController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $sort = $request->query('sort', 'id');

        $admins = Admin::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%");
        })
            ->orderBy($sort)
            ->paginate(5);

        return view('admins.index', compact('admins', 'search', 'sort'));
    }

    public function create()
    {
        return view('admins.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:admins',
            'phone' => 'nullable',
            'profile_pic' => 'nullable|image',
            'resume' => 'nullable|mimes:pdf,doc,docx',
        ]);

        $data = $request->only('name', 'email', 'phone');

        if ($request->hasFile('profile_pic')) {
            $file = $request->file('profile_pic');
            $fileName = $file->getClientOriginalName();
            $data['profile'] = $file->storeAs('profiles', $fileName, 'public');
        }

        if ($request->hasFile('resume')) {
            $file = $request->file('resume');
            $fileName = $file->getClientOriginalName();
            $data['resume'] = $file->storeAs('resumes', $fileName, 'public');
        }

        Admin::create($data);

        return redirect()->route('admins.index')->with('success', 'Admin created successfully');
    }

    public function edit($id)
    {
        $user = Admin::findOrFail($id);
        return view('admins.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = Admin::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:admins,email,' . $user->id,
            'phone' => 'nullable',
        ]);

        $data = $request->only('name', 'email', 'phone');

        if ($request->hasFile('profile_pic')) {
            if ($user->profile) Storage::disk('public')->delete($user->profile);
            $file = $request->file('profile_pic');
            $fileName = $file->getClientOriginalName();
            $data['profile'] = $file->storeAs('profiles', $fileName, 'public');
        }

        if ($request->hasFile('resume')) {
            if ($user->resume) Storage::disk('public')->delete($user->resume);
            $file = $request->file('resume');
            $fileName = $file->getClientOriginalName();
            $data['resume'] = $file->storeAs('resumes', $fileName, 'public');
        }

        $user->update($data);

        return redirect()->route('admins.index')->with('success', 'Admin updated successfully');
    }

    public function destroy($id)
    {
        $user = Admin::findOrFail($id);
        if ($user->profile) Storage::disk('public')->delete($user->profile);
        if ($user->resume) Storage::disk('public')->delete($user->resume);
        $user->delete();
        return back()->with('success', 'User deleted');
    }

    public function exportCsv()
    {
        $filename = 'users.csv';
        $users = Admin::all();

        $handle = fopen($filename, 'w+');
        fputcsv($handle, ['ID', 'Name', 'Email', 'Phone', 'Profile', 'Resume']);

        foreach ($users as $user) {
            fputcsv($handle, [$user->id, $user->name, $user->email, $user->phone, $user->profile, $user->resume]);
        }

        fclose($handle);

        return response()->download($filename)->deleteFileAfterSend(true);
    }

    public function exportPdf()
    {
        $users = Admin::all();
        $pdf = Pdf::loadView('admins.pdf', compact('users'));
        return $pdf->download('users.pdf');
    }
}
