<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class InstructorsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $lecturers = User::all();
        $lecturers = User::where('role', 1)
            ->orderBy('id', 'asc')
            ->get();
        return view('admin.tableLecturer', compact('lecturers'));
    }

    public function indexStudent()
    {
        // $lecturers = User::all();
        $students = User::where('role', 2)
            ->orderBy('id', 'asc')
            ->get();
        return view('admin.tableStudent', compact('students'));
    }

    public function indexAdmin()
    {
        // $lecturers = User::all();
        $admins = User::where('role', 0)
            ->orderBy('id', 'asc')
            ->get();
        return view('admin.tableAdmin', compact('admins'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.createInstructor');
    }
    public function createStudent()
    {
        return view('admin.createStudent');
    }
    public function createAdmin()
    {
        return view('admin.createAdmin');
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
    public function edit(User $slug)
    {
        $user = User::findOrFail($slug->id);
        // Pass the user data to the view for editing
        return view('admin.createInstructor', compact('user'));
    }

    public function editStudent(User $slug)
    {
        $user = User::findOrFail($slug->id);
        // Pass the user data to the view for editing
        return view('admin.createStudent', compact('user'));
    }

    public function editAdmin(User $slug)
    {
        $user = User::findOrFail($slug->id);
        // Pass the user data to the view for editing
        return view('admin.createAdmin', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $slug)
    {
        $user = User::where('slug', $slug)->firstOrFail();
        // dd($user->role);
        // Log::info('User found', ['user' => $user]);
        // Validate request
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'account_id' => 'required|string|max:255|unique:users,account_id,' . $user->id,
            'old_password' => 'nullable|string|min:8',
            'new_password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->account_id = $request->account_id;
        // Log::info('Updated user details', ['name' => $user->name, 'email' => $user->email, 'account_id' => $user->account_id]);

        if ($request->filled('old_password')) {
            // Log::info('Old password provided');

            if (Hash::check($request->old_password, $user->password)) {
                // Log::info('Old password is correct');
                if ($request->new_password === $request->new_password_confirmation) {
                    // Log::info('New password confirmed');
                    $user->password = Hash::make($request->new_password);
                    // Log::info('Password updated');
                } else {
                    // Log::warning('New password confirmation does not match');
                    return redirect()->back()->withErrors(['new_password_confirmation' => 'New password and confirm password do not match.']);
                }
            } else {
                // Log::warning('Old password is incorrect');
                return redirect()->back()->withErrors(['old_password' => 'Old password is incorrect.']);
            }
        }

        $user->save();
        // Log::info('User updated successfully', ['user' => $user]);

        if ($user->role == 2) {
            return redirect()->route('tableStudent.index')->with('success', 'User updated successfully.');
        } elseif ($user->role == 1 ) {
            return redirect()->route('tableLecturer.index')->with('success', 'User updated successfully.');
        }else if($user->role == 0){
            return redirect()->route('tableAdmin.index')->with('success', 'User updated successfully.');
        }else{
            return redirect()->back()->with('error', 'Role not recognized.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $slug)
    {
        // Find the user by ID
        $user = User::findOrFail($slug->id);
        // Delete the user
        $user->delete();
        // Redirect back with a success message
        return back()->with('success', 'User deleted successfully');
    }

    public function inactiveStudents(Request $request)
    {
        $studentIds = $request->input('student_ids', []);

        if (!empty($studentIds)) {
            User::whereIn('id', $studentIds)
                ->where('is_active', true)
                ->update(['is_active' => false]);
        } else {
            return redirect()->route('tableStudent.index')->with('error', 'No students selected for deactivation.');
        }

        return redirect()->route('tableStudent.index')->with('success', 'Selected students have been deactivated.');
    }

    public function activeStudents(Request $request)
    {
        $studentIds = $request->input('student_ids', []);

        if (!empty($studentIds)) {
            // Chỉ cập nhật những user có is_active == false
            User::whereIn('id', $studentIds)
                ->where('is_active', false)
                ->update(['is_active' => true]);
        } else {
            return redirect()->route('tableStudent.index')->with('error', 'No students selected for activation.');
        }
        return redirect()->route('tableStudent.index')->with('success', 'Selected students have been activated.');
    }

    public function inactiveLecturers(Request $request)
    {
        $studentIds = $request->input('student_ids', []);

        if (!empty($studentIds)) {
            User::whereIn('id', $studentIds)
                ->where('is_active', true)
                ->update(['is_active' => false]);
        } else {
            return redirect()->route('tableLecturer.index')->with('error', 'No lecturers selected for deactivation.');
        }

        return redirect()->route('tableLecturer.index')->with('success', 'Selected lecturers have been deactivated.');
    }

    public function activeLecturers(Request $request)
    {
        $studentIds = $request->input('student_ids', []);

        if (!empty($studentIds)) {
            // Chỉ cập nhật những user có is_active == false
            User::whereIn('id', $studentIds)
                ->where('is_active', false)
                ->update(['is_active' => true]);
        } else {
            return redirect()->route('tableLecturer.index')->with('error', 'No lecturers selected for activation.');
        }
        return redirect()->route('tableLecturer.index')->with('success', 'Selected lecturers have been activated.');
    }
}
