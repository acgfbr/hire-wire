<?php

namespace App\Actions\Account;

use App\Models\User;
use App\Models\Account;
use App\Models\SavingsAccount;
use App\Models\CheckingAccount;
use App\Models\InvestmentAccount;

class CreateAccountAction
{
    public function execute(array $data)
    {
        $user = User::findOrFail($data['user_id']);
        
        $accountClass = match ($data['type']) {
            'savings' => SavingsAccount::class,
            'checking' => CheckingAccount::class,
            'investment' => InvestmentAccount::class,
            default => throw new \InvalidArgumentException('Invalid account type'),
        };

        $account = new $accountClass([
            'user_id' => $user->id,
            'balance' => 0
        ]);

        $account->save();

        return $account;
    }
}
