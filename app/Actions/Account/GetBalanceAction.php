<?php

namespace App\Actions\Account;

use App\Models\Account;

class GetBalanceAction
{
    public function execute(int $accountId): float
    {
        $account = Account::where('id', $accountId)
            ->where('user_id', auth()->user()->id)
            ->firstOrFail();
            
        return $account->getBalance();
    }
}
