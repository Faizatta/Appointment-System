<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Carbon\Carbon;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'phone' => ['required', 'string', 'regex:/^(?:\+92|0)3\d{9}$/'],
            ],
            [
                'phone.regex' => 'Phone number must be a valid Pakistani number, e.g., 03001234567 or +923001234567.',
            ]
        );
        $phone = preg_replace('/[^0-9]/', '', $request->phone);

        if (substr($phone, 0, 2) === "03") {
            $phone = "+92" . substr($phone, 1);
        } elseif (substr($phone, 0, 2) === "92") {
            $phone = "+$phone";
        }

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $phone,
        ]);


        $otp = rand(100000, 999999);
        $user->otp_code = $otp;
        $user->otp_expires_at = Carbon::now()->addMinutes(5);
        $user->save();


        // Login user
        Auth::login($user);

        // Redirect to phone verification page
        return redirect()->route('verify.phone')->with('phone', $user->phone);
    }
}
