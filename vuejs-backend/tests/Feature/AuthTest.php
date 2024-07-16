<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected $baseUrl;

    protected function setUp(): void
    {
        parent::setUp();
        $this->baseUrl = 'http://127.0.0.1:8000';
    }

    /**
     * @test
     */
    public function it_can_register_a_user()
    {
        $data = [
            "name" => "John Doe",
            "email" => "john@example.com",
            "password" => "password123",
            "password_confirmation" => "password123",
            "role" => "admin"
        ];

        $response = $this->postJson("{$this->baseUrl}/api/register", $data);
        $response->assertStatus(201);

        $this->assertDatabaseHas('users', [
            'email' => 'john@example.com'
        ]);
    }

    /**
     * @test
     */
    public function it_can_login_a_user()
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => bcrypt('password123')
        ]);

        $data = [
            "email" => "john@example.com",
            "password" => "password123",
        ];

        $response = $this->postJson("{$this->baseUrl}/api/login", $data);
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'token'
        ]);
    }
}
