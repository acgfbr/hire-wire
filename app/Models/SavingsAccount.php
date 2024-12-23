<?php

namespace App\Models;

class SavingsAccount extends Account
{
    protected $table = 'accounts';

    protected static function booted()
    {
        static::creating(function ($account) {
            $account->type = 'savings';
        });
    }

    public function applyMonthlyInterest(): void
    {
        $interestRate = 0.00001; // 0.001%
        $interest = $this->balance * $interestRate;
        $this->balance += $interest;
        $this->save();
    }
}
