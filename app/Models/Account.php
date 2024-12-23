<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Account extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'type',
        'balance'
    ];

    protected $casts = [
        'balance' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function deposit(float $amount): void
    {
        $this->balance += $amount;
        $this->save();
    }

    public function getBalance(): float
    {
        return $this->balance;
    }
}
