<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Carbon\Carbon;
use Twilio\Rest\Client;

class PhoneVerificationController extends Controller
{
    // Step 1: Show forgot-password form
    public function showForm()
    {
        return view('auth.forgot-password');
    }

    // Step 2: Handle submission (email or phone)
    public function send(Request $request)
    {
        $request->validate(['identifier' => 'required']);

        $identifier = $request->identifier;

        // Email flow
        if (filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $identifier)->first();
            if (!$user) return back()->withErrors(['identifier' => 'User not found.']);

            Password::sendResetLink(['email' => $user->email]);
            return back()->with('status', 'Password reset link sent to your email!');
        }

        // Phone flow
        $user = User::where('phone', $identifier)->first();
        if (!$user) return back()->withErrors(['identifier' => 'User not found.']);

        // Generate OTP
        $otp = rand(100000, 999999);
        $user->otp_code = $otp;
        $user->otp_expires_at = Carbon::now()->addMinutes(5);
        $user->save();

        // Send SMS via Twilio
        $twilio = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));
        $twilio->messages->create($user->phone, [
            'from' => env('TWILIO_FROM'),
            'body' => "Your OTP for password reset is: $otp. It expires in 5 minutes."
        ]);

        $request->session()->put('phone', $user->phone);

        return redirect()->route('verify.phone');
    }

    // Step 3: Show OTP form
    public function showOTPForm(Request $request)
    {
        $phone = $request->session()->get('phone');
        if (!$phone) return redirect()->route('password.request');

        return view('auth.verify-phone', compact('phone'));
    }

    // Step 4: Verify OTP
    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $phone = $request->session()->get('phone');
        $user = User::where('phone', $phone)->first();

        if (!$user) return back()->withErrors(['phone' => 'User not found.']);
        if ($user->otp_code != $request->otp) return back()->withErrors(['otp' => 'Invalid OTP.']);
        if (Carbon::now()->greaterThan($user->otp_expires_at)) return back()->withErrors(['otp' => 'OTP expired.']);

        // OTP correct → allow password reset
        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->phone_verified = true;
        $user->save();

        return redirect()->route('reset.password.phone')->with('phone', $phone);
    }

    // Step 5: Show password reset form
    public function showResetForm(Request $request)
    {
        $phone = $request->session()->get('phone');
        if (!$phone) return redirect()->route('password.request');

        return view('auth.reset-password-phone', compact('phone'));
    }

    // Step 6: Reset password for phone users
    public function resetPassword(Request $request)
    {
        $request->validate([
            'phone' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        $user = User::where('phone', $request->phone)->first();
        if (!$user) return back()->withErrors(['phone' => 'User not found.']);

        $user->password = Hash::make($request->password);
        $user->save();

        $request->session()->forget('phone');

        return redirect()->route('login')->with('success', 'Password reset successfully!');
    }
}
