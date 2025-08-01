<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Student;
use App\Models\Puzzle;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{

    public function index()
    {
        if (Auth::check()) {
            $user = Auth::user();

            return match ($user->role) {
                'admin' => redirect()->route('admin.dashboard'),
                'student' => redirect()->route('game.index'),
                default => redirect()->route('game.index'),
            };
        }

        return redirect()->route('login'); // login if not logged in
    }

    // Show login form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Handle login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (auth()->user()->role === 'admin') {
                return redirect('/admin');
            }

            return redirect('/game');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ]);
    }

    // Logout logic
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    // Show registration form
    public function showStudentRegisterForm()
    {
        return view('auth.register');
    }


    //handle register
    public function registerStudent(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student',
        ]);

        Student::create([
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);

        Auth::login($user);

        return redirect()->route('game.index');
    }

    public function adminDashboard() {

        $stats = [
            'total_students' => Student::count(),
            'total_puzzles' => Puzzle::count(),
            'active_puzzles' => Puzzle::where('status', 'active')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
