<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;


class GoogleController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback(Request $request)
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        // ✅ Only accept emails from dzg-ev.com
        if (!Str::endsWith($googleUser->getEmail(), '@dzg-ev.com')) {
            abort(403, 'Only @dzg-ev.com accounts are allowed.');
        }

        // ✅ Must already exist in the database
        $user = User::where('email', $googleUser->getEmail())->first();

        if (! $user) {
            abort(403, 'This email is not authorized. Ask an admin to add you.');
        }

        // ✅ Log in and regenerate session
        Auth::login($user, remember: true);

        $request->session()->regenerate();

        return redirect()->intended('/admin');
    }



}