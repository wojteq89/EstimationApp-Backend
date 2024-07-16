<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Estimation;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EstimationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $baseUrl;
    protected $user;
    protected $token;
    protected $client;
    protected $project;
    protected $estimation;

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

        $this->client = Client::create([
            'name' => 'Test Client',
            'description' => 'Description of Test Client',
            'logo' => 'client_logo.png',
            'country' => 'Poland',
            'email' => 'client@example.com',
        ]);

        $this->project = Project::create([
            'name' => 'Test Project',
            'description' => 'Description of Test Project',
            'client_id' => $this->client->id,
        ]);

        $this->estimation = Estimation::create([
            'name' => 'Test Estimation',
            'description' => 'Description of Test Estimation',
            'project_id' => $this->project->id,
            'client_id' => $this->client->id,
            'date' => now()->format('Y-m-d'),
            'type' => 'hourly',
            'amount' => 1000,
        ]);
    }

    /**
     * @test
     */
    public function it_can_fetch_estimations()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->get("{$this->baseUrl}/api/estimations");

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function it_can_show_an_estimation()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->get("{$this->baseUrl}/api/estimations/{$this->estimation->id}");

        $response->assertStatus(200)
            ->assertJson([
                'id' => $this->estimation->id,
                'name' => 'Test Estimation',
            ]);
    }

    /**
     * @test
     */
    public function it_can_update_an_estimation()
    {
        $data = [
            'name' => 'Updated Estimation Name',
            'description' => 'Updated description',
            'project_id' => $this->project->id,
            'date' => now()->addDays(1)->format('Y-m-d'),
            'type' => 'fixed',
            'amount' => 1500,
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson("{$this->baseUrl}/api/estimations/{$this->estimation->id}", $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('estimations', [
            'id' => $this->estimation->id,
            'name' => 'Updated Estimation Name',
            'description' => 'Updated description',
        ]);
    }

    /**
     * @test
     */
    public function it_can_delete_an_estimation()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->delete("{$this->baseUrl}/api/estimations/{$this->estimation->id}");

        $response->assertStatus(204);

        $deletedEstimation = Estimation::find($this->estimation->id);
        $this->assertNull($deletedEstimation, 'Failed to delete estimation from database.');
    }
}

