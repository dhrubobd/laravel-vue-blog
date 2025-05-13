<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function checkLogin(Request $request)
    {
        //dd($request->all());
        $email = "admin@admin.com";
        $password = "admin";

        if (($email == $request->input('email')) && ($password == $request->input('password'))) {
            return  redirect()->back()->with('success', 'Login successful');
        } else {
            return redirect()->back()->with('error', 'Invalid credentials');
        }
    }

    public function showLoginForm()
    {
        return Inertia::render('Auth/Login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/')->with('success', 'Login Success!');
        }

        return redirect()->back()->with('error', 'Login Failed');
    }

    public function showRegisterForm()
    {
        return Inertia::render('Auth/Register');
    }

    public function register(Request $request)
    {
        try {
            
            $request->validate([
                'username' => 'required|max:100',
                'email' => 'required|email|max:100',
                'password' => 'required|min:6'
            ]);
            
            User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return redirect()->route('login')->with('success', 'Registration Successful!');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Registration Failed');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('error', 'Logged Out');
    }
}
