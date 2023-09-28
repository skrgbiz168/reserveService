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
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('Auth/Register',[
            'start_at' => $request->start_at,
            'stay_time' => $request->stay_time,
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:'.User::class,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'start_at' => 'nullable|string',
            'stay_time' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'administer_flag' => false,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);

        if ($request->start_at !== null && $request->stay_time !== null) {
            return redirect()->route('user.reserve.create', [
                'start_at' => $request->start_at,
                'stay_time' => $request->stay_time,
            ]);
        } else {
            return redirect(RouteServiceProvider::USER_HOME);
        }

    }
}
