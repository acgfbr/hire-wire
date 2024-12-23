<?php

namespace App\Actions\Account;

use App\Models\Account;
use App\Models\CheckingAccount;
use App\Models\InvestmentAccount;
use App\Models\SavingsAccount;

class DepositAction
{
    public function execute(array $data): Account
    {
        $baseAccount = Account::findOrFail($data['account_id']);
        
        $account = match ($baseAccount->type) {
            'checking' => CheckingAccount::find($data['account_id']),
            'investment' => InvestmentAccount::find($data['account_id']),
            'savings' => SavingsAccount::find($data['account_id']),
            default => throw new \InvalidArgumentException('Invalid account type'),
        };

        $account->deposit($data['amount']);
        return $account;
    }
}
