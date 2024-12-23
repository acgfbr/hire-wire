<?php

namespace App\Actions\Account;

use App\Models\Account;
use App\Models\SavingsAccount;
use App\Models\InvestmentAccount;
use App\Models\CheckingAccount;

class ApplyMonthlyInterestAction
{
    public function execute(): void
    {
        // Apply interest to savings accounts
        SavingsAccount::where('type', 'savings')->chunk(100, function ($accounts) {
            foreach ($accounts as $account) {
                $account->applyMonthlyInterest();
            }
        });

        // Apply interest to checking accounts
        CheckingAccount::where('type', 'checking')->chunk(100, function ($accounts) {
            foreach ($accounts as $account) {
                $account->applyMonthlyInterest();
            }
        });

        // Apply interest to investment accounts
        InvestmentAccount::where('type', 'investment')->chunk(100, function ($accounts) {
            foreach ($accounts as $account) {
                $account->applyMonthlyInterest();
            }
        });
    }
}
