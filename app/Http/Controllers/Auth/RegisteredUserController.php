<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

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
            'first_name'      => ['required', 'string', 'max:255'],
            'last_name'       => ['required', 'string', 'max:255'],
            'middle_name'     => ['nullable', 'string', 'max:255'],
            'email'           => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'contact_number'  => ['required', 'string', 'regex:/^(\+63|0)\d{10}$/'],
            'student_id'      => ['required', 'string', 'max:50', 'unique:users,student_id'],
            'program'         => ['required', 'string', 'max:255'],
            'username'        => ['required', 'string', 'max:50', 'unique:users,username'],
            'gender'          => ['required', 'in:Male,Female,Other'],
            'birthdate'       => ['required', 'date', 'before:today'],
            'address'         => ['required', 'string', 'max:500'],
            'password'        => [
                'required',
                'confirmed',
                Rules\Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols()
            ],
        ], [
            'contact_number.regex' => 'Contact number must be in PH format (+63 or 0 followed by 10 digits).',
        ]);

        $user = User::create([
            'first_name'     => $request->first_name,
            'last_name'      => $request->last_name,
            'middle_name'    => $request->middle_name,
            'name'           => $request->last_name . ', ' . $request->first_name . ' ' . $request->middle_name, 
            'email'          => $request->email,
            'contact_number' => $request->contact_number,
            'student_id'     => $request->student_id,
            'program'        => $request->program,
            'username'       => $request->username,
            'gender'         => $request->gender,
            'birthdate'      => $request->birthdate,
            'address'        => $request->address,
            'password'       => Hash::make($request->password),
            'role'           => 'student', // Default role
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
