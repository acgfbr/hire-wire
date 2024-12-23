<?php

namespace Tests\Feature\Account;

use Tests\TestCase;
use App\Models\User;
use Laravel\Passport\Passport;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateAccountTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_user_can_create_savings_account(): void
    {
        Passport::actingAs($this->user);

        $response = $this->postJson('/api/accounts', [
            'type' => 'savings'
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'account' => [
                    'id',
                    'user_id',
                    'type',
                    'balance',
                    'created_at',
                    'updated_at'
                ]
            ]);

        $this->assertDatabaseHas('accounts', [
            'user_id' => $this->user->id,
            'type' => 'savings'
        ]);
    }

    public function test_user_can_create_checking_account(): void
    {
        Passport::actingAs($this->user);

        $response = $this->postJson('/api/accounts', [
            'type' => 'checking'
        ]);

        $response->assertStatus(201);
        
        $this->assertDatabaseHas('accounts', [
            'user_id' => $this->user->id,
            'type' => 'checking'
        ]);
    }

    public function test_user_cannot_create_account_with_invalid_type(): void
    {
        Passport::actingAs($this->user);

        $response = $this->postJson('/api/accounts', [
            'type' => 'invalid'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['type']);
    }

    public function test_unauthenticated_user_cannot_create_account(): void
    {
        $response = $this->postJson('/api/accounts', [
            'type' => 'savings'
        ]);

        $response->assertStatus(401);
    }
}
