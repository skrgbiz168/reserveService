<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Socialite\Facades\Socialite;

class ProviderLoginController extends Controller
{

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // リダイレクトの処理の追加
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    // プロバイダーからのコールバック時の処理の追加
    public function handleProviderCallback($provider)
    {
        $provided_user = Socialite::driver($provider)->stateless()->user();

        $user = User::where('provider', $provider)
            ->where('provided_user_id', $provided_user->id)
            ->first();

        if ($user === null) {
            $user = User::create([
                'name'               => $provided_user->name,
                'provider'           => $provider,
                'provided_user_id'   => $provided_user->id,
            ]);
        }

        Auth::login($user);
    }
}