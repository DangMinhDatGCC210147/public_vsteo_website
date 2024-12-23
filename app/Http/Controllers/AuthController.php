<?php

namespace App\Http\Controllers;

use App\Imports\AdminsImport;
use App\Imports\LecturersImport;
use App\Imports\UsersImport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Str;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

use function Ramsey\Uuid\v1;

class AuthController extends Controller
{
    public function registerExcelStudents(Request $request)
    {
        if ($request->hasFile('excel_file')) {
            try {
                Log::info("Received file: " . $request->file('excel_file')->getClientOriginalName());

                Excel::import(new UsersImport, $request->file('excel_file'));

                return redirect()->route('tableStudent.index')->with('success', 'All users registered successfully');
            } catch (\Exception $e) {
                Log::error("Error during Excel import: " . $e->getMessage());
                return redirect()->route('tableStudent.index')->with('error', 'There was an error processing the Excel file. Or there is a duplicate account in the system.');
            }
        } else {
            return redirect()->route('tableStudent.index')->with('error', 'No file was uploaded.');
        }
    }

    public function registerExcelLecturers(Request $request)
    {
        if ($request->hasFile('excel_file')) {
            try {
                Log::info("Received file: " . $request->file('excel_file')->getClientOriginalName());

                Excel::import(new LecturersImport, $request->file('excel_file'));

                return redirect()->route('tableLecturer.index')->with('success', 'All users registered successfully');
            } catch (\Exception $e) {
                Log::error("Error during Excel import: " . $e->getMessage());
                return redirect()->route('tableLecturer.index')->with('error', 'There was an error processing the Excel file. Or there is a duplicate account in the system.');
            }
        } else {
            return redirect()->route('tableLecturer.index')->with('error', 'No file was uploaded.');
        }
    }

    public function registerExcelAdmins(Request $request)
    {
        if ($request->hasFile('excel_file')) {
            try {
                Log::info("Received file: " . $request->file('excel_file')->getClientOriginalName());

                Excel::import(new AdminsImport, $request->file('excel_file'));

                return redirect()->route('tableAdmin.index')->with('success', 'All users registered successfully');
            } catch (\Exception $e) {
                Log::error("Error during Excel import: " . $e->getMessage());
                return redirect()->route('tableAdmin.index')->with('error', 'There was an error processing the Excel file. Or there is a duplicate account in the system.');
            }
        } else {
            return redirect()->route('tableAdmin.index')->with('error', 'No file was uploaded.');
        }
    }

    public function registerPost(Request $request)
    {
        if (User::where('email', $request->email)->exists()) {
            return back()->with('error', 'Email: ' . $request->email . 'already exists.');
        }

        // Kiểm tra xem account_id đã tồn tại chưa
        if (User::where('account_id', $request->account_id)->exists()) {
            return back()->with('error', 'Student ID ' . $request->account_id . ' already exists.');
        }

        if ($request->filled('new_password') && $request->new_password !== $request->password) {
            return back()->with('error', 'Passwords do not match.');
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->account_id = $request->account_id;
        $user->role = $request->role;
        $user->password = Hash::make($request->password);
        $user->is_active = true;
        $user->save();

        if ($user->role == 1) {
            return redirect()->route('tableLecturer.index')->with('success', 'Registered successfully');
        } else if ($user->role == 0) {
            return redirect()->route('tableAdmin.index')->with('success', 'Registered successfully');
        } else {
            return redirect()->route('tableStudent.index')->with('success', 'Registered successfully');
        }
    }

    public function showlogin()
    {
        return view('auth.login-vstep');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Tìm người dùng với email và kiểm tra is_active
        $user = User::where('email', $credentials['email'])->where('is_active', true)->first();

        if ($user && Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Lưu thông tin người dùng đã đăng nhập vào session
            $request->session()->put('role', $user->role);
            $request->session()->put('user_name', $user->name);
            $request->session()->put('user_email', $user->email);
            $request->session()->put('account_id', $user->id);
            $request->session()->put('user_id', $user->account_id);
            $request->session()->put('slug', $user->slug);

            // Kiểm tra thông tin session đã lưu
            if ($user->role == 1) {
                return redirect()->route('admin.index')->with('success', 'Login successfully as Lecturer!');
            } elseif ($user->role == 0) {
                return redirect()->route('admin.index')->with('success', 'Login successfully as Admin!');
            } else {
                return redirect()->route('student.index')->with('success', 'Login successfully as student!');
            }
        }

        return back()->withErrors([
            'email' => 'Wrong email or password, please re-enter for information.',
            'is_active' => 'Or Your account is inactive. Please contact support.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        // Xoá session đã tạo
        $request->session()->invalidate();

        // Tạo lại token CSRF mới
        $request->session()->regenerateToken();

        // Chuyển hướng người dùng về trang chủ hoặc trang đăng nhập
        return redirect('/');
    }

    public function changePassword()
    {
        return view('auth.changePassword-vstep');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'old_password' => 'required|string|min:8',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'No account found with that email.']);
        }

        if (!Hash::check($request->old_password, $user->password)) {
            return back()->withErrors(['old_password' => 'Your old password is incorrect.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('student.login')->with('success', 'Password updated successfully.');
    }
}
