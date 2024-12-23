<?php

namespace App\Models;

class CheckingAccount extends Account
{
    protected $table = 'accounts';

    protected static function booted()
    {
        static::creating(function ($account) {
            $account->type = 'checking';
        });
    }

    public function deposit(float $amount): void
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Amount must be greater than zero');
        }

        $this->balance += ($amount + 0.50); // Adds R$0.50 bonus
        $this->save();
    }

    public function applyMonthlyInterest(): void
    {
        $interestRate = 0.001; // 0.1%
        $interest = $this->balance * $interestRate;
        $this->balance += $interest;
        $this->save();
    }
}
