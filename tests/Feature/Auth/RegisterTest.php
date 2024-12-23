<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends TestCase
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

    public function test_user_can_register_with_valid_data(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'cpf' => '12345678901',
            'email' => 'john@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(201)
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

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com',
            'cpf' => '12345678901'
        ]);
    }

    public function test_user_cannot_register_with_existing_email(): void
    {
        User::factory()->create([
            'email' => 'john@example.com',
            'cpf' => '11111111111'
        ]);

        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'cpf' => '22222222222',
            'email' => 'john@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_user_cannot_register_with_existing_cpf(): void
    {
        User::factory()->create([
            'cpf' => '12345678901'
        ]);

        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'cpf' => '12345678901',
            'email' => 'new@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['cpf']);
    }
}
