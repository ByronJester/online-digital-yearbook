<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\{ User, OtpMessage };
use Carbon\Carbon;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $otp = OtpMessage::where('email', $request->email)->where('code', $request->code)->first();

        if(!$otp) {
            return redirect()->back()->withErrors(['error' => 'An error occurred.']);
        }

        $request->authenticate();

        $request->session()->regenerate();

        $auth = auth()->user();


        $route = null;

        if($auth->user_type == 'system_admin') {
            $route = RouteServiceProvider::ADMIN;
        }

        if($auth->user_type == 'school_staff') {
            $route = RouteServiceProvider::STAFF;
        }

        if($auth->user_type == 'school_alumni') {
            $route = RouteServiceProvider::ALUMNI;
        }

        User::where('id', $auth->id)->update([
            'last_logged_in' => Carbon::now(),
            'logout_at' => null
        ]);

        OtpMessage::where('email', $auth->email)->delete();

        return redirect()->intended($route);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $user->logout_at = Carbon::now();
        $user->save();

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
