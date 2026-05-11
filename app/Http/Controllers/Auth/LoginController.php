<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('pages.auth.login');
    }

    /**
     * Handle login attempt
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8'],
            'remember' => ['nullable', 'boolean'],
        ]);

        // Attempt authentication with status check
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Block inactive users
            if (!$user->status) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account is currently inactive. Please contact administrator.',
                ])->withInput($request->only('email'));
            }

            // Role-based redirection
            return $this->authenticated($request, $user);
        }

        throw ValidationException::withMessages([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Redirect based on user role
     */
   
protected function authenticated(Request $request, $user)
{
    
    return redirect()->route('dashboard');
}
}