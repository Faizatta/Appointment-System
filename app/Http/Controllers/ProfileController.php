<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Show the logged-in user's profile page.
     */
    public function index(Request $request): View
    {
        // Refresh user instance to get latest data
        $user = $request->user()->fresh();

        return view('profiles.index', compact('user'));
    }

    /**
     * Edit profile (same view, optional).
     */
    public function edit(Request $request): View
    {
        return view('profiles.index', [
            'user' => $request->user()->fresh(),
        ]);
    }

    /**
     * Update profile details OR password.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // --------------------------
        // Handle password update
        // --------------------------
        if ($request->filled('current_password') && $request->filled('password')) {
            if (! Hash::check($request->current_password, $user->password)) {
                return Redirect::back()->withErrors([
                    'current_password' => 'Your current password is incorrect.',
                ]);
            }

            $request->validate([
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);

            $user->password = Hash::make($request->password);
            $user->save();

            return Redirect::route('profiles.index')
                ->with('status', 'Password updated successfully!');
        }

        // --------------------------
        // Handle profile picture
        // --------------------------
        if ($request->hasFile('profile_picture')) {
            // Delete old picture if exists
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            $path = $request->file('profile_picture')->store('profiles', 'public');
            $user->profile_picture = $path;
        }

   
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->bio = $request->input('bio');
        $user->address = $request->input('address');
        $user->dob = $request->input('dob');

        // Reset email verification if email changed
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profiles.index')
            ->with('status', 'Profile updated successfully!');
    }

    /**
     * Delete the user's account (except admin).
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        if ($user->role === 'admin' || $user->email === 'faiizatta@gmail.com') {
            return Redirect::route('profiles.index')
                ->with('error', 'Admin account cannot be deleted.');
        }

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/')->with('status', 'Your account has been deleted.');
    }
}
