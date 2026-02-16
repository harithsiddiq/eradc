<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => __('auth.login.email_required'),
            'email.email' => __('auth.login.email_invalid'),
            'password.required' => __('auth.login.password_required'),
        ]);

        $remember = (bool) $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => __('auth.failed'),
        ])->withInput($request->only('email'));
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8'],
            'level' => ['required', 'in:fresh,mid,consultant'],
        ], [
            'name.required' => __('auth.register.name_required'),
            'name.max' => __('auth.register.name_max'),
            'email.required' => __('auth.register.email_required'),
            'email.email' => __('auth.register.email_invalid'),
            'email.unique' => __('auth.register.email_unique'),
            'password.required' => __('auth.register.password_required'),
            'password.min' => __('auth.register.password_min'),
            'password.confirmed' => __('auth.register.password_confirmed'),
            'level.required' => __('auth.register.level_required'),
            'level.in' => __('auth.register.level_required'),
        ]);

        $ip = $request->ip();
        $country = null; $region = null; $city = null;
        try {
            $resp = Http::timeout(3)->get("https://ipapi.co/{$ip}/json/");
            if ($resp->ok()) {
                $json = $resp->json();
                $ip = $json['ip'] ?? $ip;
                $country = $json['country_name'] ?? null;
                $region = $json['region'] ?? null;
                $city = $json['city'] ?? null;
            } 
        } catch (\Throwable $e) {
        }

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'type' => 'user',
            'level' => $data['level'],
            'signup_ip' => $ip,
            'signup_country' => $country,
            'signup_region' => $region,
            'signup_city' => $city,
        ]);

        event(new \Illuminate\Auth\Events\Registered($user));

        Auth::login($user);

        return redirect()->route('verification.notice');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
