<?php

namespace App\Http\Controllers;

use App\Actions\Auth\LoginUserAction;
use App\Actions\Auth\LogoutUserAction;
use App\Actions\Auth\RegisterUserAction;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private RegisterUserAction $registerUserAction,
        private LoginUserAction $loginUserAction,
        private LogoutUserAction $logoutUserAction
    ) {}

    public function register(RegisterRequest $request)
    {
        $result = $this->registerUserAction->execute($request->validated());
        return response()->json($result, 201);
    }

    public function login(LoginRequest $request)
    {
        $result = $this->loginUserAction->execute($request->validated());
        return response()->json($result);
    }

    public function logout(Request $request)
    {
        $this->logoutUserAction->execute($request);
        return response()->json(['message' => 'Successfully logged out']);
    }
}
