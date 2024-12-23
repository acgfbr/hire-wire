<?php

namespace Tests\Feature\Account;

use Tests\TestCase;
use App\Models\User;
use App\Models\SavingsAccount;
use App\Models\CheckingAccount;
use App\Models\InvestmentAccount;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DepositTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private SavingsAccount $savingsAccount;
    private CheckingAccount $checkingAccount;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        
        $this->savingsAccount = new SavingsAccount();
        $this->savingsAccount->user_id = $this->user->id;
        $this->savingsAccount->save();

        $this->checkingAccount = new CheckingAccount();
        $this->checkingAccount->user_id = $this->user->id;
        $this->checkingAccount->save();
    }

    public function test_user_can_deposit_to_savings_account(): void
    {
        Passport::actingAs($this->user);

        $response = $this->postJson("/api/accounts/{$this->savingsAccount->id}/deposit", [
            'amount' => 100
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'new_balance'
            ]);

        $this->assertEquals(100.00, $this->savingsAccount->fresh()->balance);
    }

    public function test_user_can_deposit_to_checking_account(): void
    {
        Passport::actingAs($this->user);

        $response = $this->postJson("/api/accounts/{$this->checkingAccount->id}/deposit", [
            'amount' => 100
        ]);

        $response->assertStatus(200);
        $this->assertEquals(100.50, $this->checkingAccount->fresh()->balance);
    }

    public function test_user_can_deposit_to_investment_account(): void
    {
        $investmentAccount = new InvestmentAccount();
        $investmentAccount->user_id = $this->user->id;
        $investmentAccount->save();

        Passport::actingAs($this->user);

        $response = $this->postJson("/api/accounts/{$investmentAccount->id}/deposit", [
            'amount' => 100
        ]);

        $response->assertStatus(200);
        $this->assertEquals(100.50, $investmentAccount->fresh()->balance);
    }

    public function test_user_cannot_deposit_negative_amount(): void
    {
        Passport::actingAs($this->user);

        $response = $this->postJson("/api/accounts/{$this->savingsAccount->id}/deposit", [
            'amount' => -100
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['amount']);
    }

    public function test_user_cannot_deposit_to_other_users_account(): void
    {
        $otherUser = User::factory()->create();
        Passport::actingAs($otherUser);

        $response = $this->postJson("/api/accounts/{$this->savingsAccount->id}/deposit", [
            'amount' => 100
        ]);

        $response->assertStatus(404);
    }
}
