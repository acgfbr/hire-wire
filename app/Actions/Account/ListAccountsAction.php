<?php

namespace App\Actions\Account;

use App\Models\User;
use App\Models\Account;
use Illuminate\Support\Collection;

class ListAccountsAction
{
    public function execute(int $userId): array
    {
        $accounts = Account::where('user_id', $userId)->get();

        return [
            'savings' => $accounts->where('type', 'savings')->values()->all(),
            'checking' => $accounts->where('type', 'checking')->values()->all(),
            'investment' => $accounts->where('type', 'investment')->values()->all(),
        ];
    }
}
