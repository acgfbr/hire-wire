<?php

namespace Tests\Feature\Account;

use Tests\TestCase;
use App\Models\User;
use App\Models\SavingsAccount;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetBalanceTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private SavingsAccount $account;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        
        $this->account = new SavingsAccount();
        $this->account->user_id = $this->user->id;
        $this->account->balance = 1000;
        $this->account->save();
    }

    public function test_user_can_get_account_balance(): void
    {
        Passport::actingAs($this->user);

        $response = $this->getJson("/api/accounts/{$this->account->id}/balance");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'balance'
            ])
            ->assertJson([
                'balance' => '1000.00'
            ]);
    }

    public function test_user_cannot_get_balance_of_other_users_account(): void
    {
        $otherUser = User::factory()->create();
        Passport::actingAs($otherUser);

        $response = $this->getJson("/api/accounts/{$this->account->id}/balance");

        $response->assertStatus(404);
    }

    public function test_unauthenticated_user_cannot_get_balance(): void
    {
        $response = $this->getJson("/api/accounts/{$this->account->id}/balance");

        $response->assertStatus(401);
    }
}
