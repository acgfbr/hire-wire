<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('passport:client', [
            '--personal' => true, 
            '--no-interaction' => true,
            '--name' => 'Test Personal Access Client'
        ]);
    }

    public function test_user_can_login_with_correct_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'cpf' => '12345678901',
            'password' => Hash::make('password123')
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'john@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'user' => [
                    'id',
                    'name',
                    'email',
                    'cpf',
                    'created_at',
                    'updated_at'
                ],
                'token'
            ]);
    }

    public function test_user_cannot_login_with_incorrect_password(): void
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'cpf' => '12345678901',
            'password' => Hash::make('password123')
        ]);

        $response = $this->postJson('/api/login', [
            'email' => 'john@example.com',
            'password' => 'wrongpassword'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_user_cannot_login_with_nonexistent_email(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }
}
