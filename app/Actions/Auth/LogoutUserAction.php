<?php

namespace App\Actions\Auth;

use Illuminate\Http\Request;

class LogoutUserAction
{
    public function execute(Request $request): void
    {
        $request->user()->token()->revoke();
    }
}
