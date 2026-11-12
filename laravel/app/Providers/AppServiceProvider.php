<?php

namespace App\Providers;

use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Гард "jwt": бере токен з заголовка Authorization: Bearer ... і знаходить користувача
        Auth::viaRequest('jwt', function (Request $request) {
            $token = $request->bearerToken();

            if (!$token) {
                return null;
            }

            try {
                $payload = JWT::decode($token, new Key(config('app.key'), 'HS256'));
            } catch (\Throwable) {
                return null;
            }

            return User::find($payload->sub);
        });
    }
}
