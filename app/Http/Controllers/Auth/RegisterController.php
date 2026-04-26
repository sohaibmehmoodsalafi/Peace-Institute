<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    public function showForm(string $role = 'student')
    {
        abort_if(!in_array($role, ['student', 'teacher']), 404);
        return view('auth.register', compact('role'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role'     => ['required', 'in:student,teacher'],
            'phone'    => ['nullable', 'string', 'max:20'],
            'timezone' => ['nullable', 'string'],
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
            'phone'    => $request->phone,
            'timezone' => $request->timezone ?? 'UTC',
        ]);

        // Create role-specific profile
        if ($request->role === 'student') {
            Student::create(['user_id' => $user->id]);
            Auth::login($user);
            return redirect()->route('student.dashboard')
                ->with('success', 'Welcome to Peace Institute!');
        }

        if ($request->role === 'teacher') {
            Teacher::create([
                'user_id'       => $user->id,
                'hourly_rate'   => $request->hourly_rate ?? 15.00,
                'status'        => 'pending',
            ]);
            Auth::login($user);
            return redirect()->route('teacher.pending');
        }
    }
}
