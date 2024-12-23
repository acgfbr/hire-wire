<?php

namespace App\Actions\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterUserAction
{
    public function execute(array $validatedData): array
    {
        $user = User::create([
            'name' => $validatedData['name'],
            'cpf' => $validatedData['cpf'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
        ]);

        $token = $user->createToken('auth-token')->accessToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }
}
