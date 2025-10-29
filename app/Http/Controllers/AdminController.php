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
         $allowedSorts = ['id', 'name', 'email', 'phone', 'created_at'];
        if (!in_array($sort, $allowedSorts)) {
            $sort = 'id';
        }

         $perPage = (int) $request->query('per_page', 5);
        if ($perPage <= 0) $perPage = 5;
        $perPage = min($perPage, 100);  

        $admins = Admin::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%");
        })
            ->orderBy($sort)
            ->paginate($perPage);

        return view('admins.index', compact('admins', 'search', 'sort'));
    }

    public function create()
    {
        return view('admins.create');
    }

    public function store(Request $request)
    {
        try {
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
            return redirect()->route('admins.index')->with('success', 'User created successfully');
        } catch (\Exception $e) {
            return redirect()->route('admins.index')->with('error', 'Failed to create user: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $user = Admin::findOrFail($id);
        return view('admins.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        try {
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
        } catch (\Exception $e) {
            return redirect()->route('admins.index')->with('error', 'Failed to update admin: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $user = Admin::findOrFail($id);
            if ($user->profile) Storage::disk('public')->delete($user->profile);
            if ($user->resume) Storage::disk('public')->delete($user->resume);
            $user->delete();
            return back()->with('info', 'User deleted');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }

    public function exportCsv()
    {
        try {
            $filename = 'users.csv';
            $users = Admin::all();

            $handle = fopen($filename, 'w+');
            fputcsv($handle, ['ID', 'Name', 'Email', 'Phone', 'Profile URL', 'Resume URL']);

            foreach ($users as $user) {
                $profileUrl = $user->profile ? asset('storage/' . $user->profile) : '';
                $resumeUrl = $user->resume ? asset('storage/' . $user->resume) : '';
                
                fputcsv($handle, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->phone,
                    $profileUrl,
                    $resumeUrl
                ]);
            }

            fclose($handle);

            session()->flash('success', 'CSV exported successfully!');
            return response()->download($filename)->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            return redirect()->route('admins.index')->with('error', 'Failed to export CSV: ' . $e->getMessage());
        }
    }

    public function exportPdf()
    {
        try {
            $users = Admin::all();
            $pdf = Pdf::loadView('admins.pdf', compact('users'));
            session()->flash('success', 'PDF exported successfully!');
            return $pdf->download('users.pdf');
        } catch (\Exception $e) {
            return redirect()->route('admins.index')->with('error', 'Failed to export PDF: ' . $e->getMessage());
        }
    }
}
