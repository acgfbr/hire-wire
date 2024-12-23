<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginUserAction
{
    public function execute(array $validatedData): array
    {
        $user = User::where('email', $validatedData['email'])->first();

        if (! $user || ! Hash::check($validatedData['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('auth-token')->accessToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}
