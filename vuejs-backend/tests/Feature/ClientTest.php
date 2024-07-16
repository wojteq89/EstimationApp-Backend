<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClientTest extends TestCase
{
    use RefreshDatabase;

    protected $baseUrl;
    protected $user;
    protected $token;

    protected function setUp(): void
    {
        parent::setUp();
        $this->baseUrl = 'http://127.0.0.1:8000';

        $this->user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => bcrypt('password123'),
            'role' => 'admin'
        ]);

        $response = $this->postJson("{$this->baseUrl}/api/login", [
            "email" => "john@example.com",
            "password" => "password123",
        ]);

        $this->token = $response->json('token');
    }

    /**
     * @test
     */
    public function it_can_fetch_clients()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->get("{$this->baseUrl}/api/clients");

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function it_can_create_a_client()
    {
        $data = [
            "name" => "Client A",
            "description" => "Description of Client A",
            "logo" => "client_a_logo.png",
            "country" => "Country A",
            "email" => "client_a@example.com",
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson("{$this->baseUrl}/api/clients", $data);

        $response->assertStatus(201);
        $this->assertDatabaseHas('clients', [
            'email' => 'client_a@example.com'
        ]);
    }

    /**
     * @test
     */
    public function it_can_update_a_client()
    {
        $client = Client::factory()->create(); // Tworzymy nowego klienta

        $data = [
            "name" => "Updated Client Name",
            "description" => "Updated description",
            "logo" => "updated_logo.png",
            "country" => "Updated Country",
            "email" => "updatedClient@example.com", // Przykładowy email dla aktualizacji
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson("{$this->baseUrl}/api/clients/{$client->id}", $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('clients', [
            'name' => 'Updated Client Name'
        ]);
    }

    /**
     * @test
     */
    public function it_can_delete_a_client()
    {
        $client = Client::factory()->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->delete("{$this->baseUrl}/api/clients/{$client->id}");

        $response->assertStatus(204);

        $deletedClient = Client::find($client->id);
        $this->assertNull($deletedClient, 'Failed to delete client from database.');
    }

}
