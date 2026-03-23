<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Throwable;

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
        $request->validate([
            'name'          => ['required', 'string', 'max:255', 'regex:/^[A-Za-z0-9 .\'-]+$/'],
            'email'         => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone'         => ['nullable', 'string', 'max:20', 'regex:/^[0-9+\-() ]*$/'],
            'address'       => ['nullable', 'string', 'max:500'],
            'password'      => [
                'required',
                'confirmed',
                'min:8',
                'max:64',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+\-=\[\]{};:\\|,.<>\/?]).+$/',
            ],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ], [
            'name.regex' => 'Name may only contain letters, numbers, spaces, and . \' - characters.',
            'phone.regex' => 'Phone may only contain numbers, spaces, and + - ( ) characters.',
            'password.regex' => 'Password must be 8-64 characters, include upper and lower case letters, a number, and a special character.'
        ]);

        $photoPath = null;
        if ($request->hasFile('profile_photo')) {
            $photoPath = $request->file('profile_photo')->store('profile-photos', 'public');
        }

        $user = User::create([
            'name'               => $request->name,
            'email'              => $request->email,
            'phone'              => $request->phone,
            'address'            => $request->address,
            'password'           => Hash::make($request->password),
            'role'               => 'customer',
            'profile_photo_path' => $photoPath,
        ]);

        try {
            // Registered event already triggers SendEmailVerificationNotification listener.
            // Dispatch it once to avoid duplicate send attempts.
            event(new Registered($user));
            $status = 'Account created! Please verify your email before logging in.';
        } catch (Throwable $e) {
            report($e);
            $status = 'Account created! Verification email could not be sent right now. Please use Resend Verification Email after logging in attempt.';
        }

        return redirect(route('login'))->with('status', $status);
    }
}
