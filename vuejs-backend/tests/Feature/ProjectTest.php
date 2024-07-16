<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $baseUrl;
    protected $user;
    protected $token;
    protected $client;
    protected $project;

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
            "name" => "Test Client",
            "description" => "Description of Test Client",
            "logo" => "test_client_logo.png",
            "country" => "Poland",
            "email" => "test_client@example.com",
        ]);

        $this->project = Project::create([
            "name" => "Test Project",
            "description" => "Description of Test Project",
            "client_id" => $this->client->id,
        ]);
    }

    /**
     * @test
     */
    public function it_can_fetch_projects()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->get("{$this->baseUrl}/api/projects");

        $response->assertStatus(200);
    }

    /**
     * @test
     */
    public function it_can_create_a_project()
    {
        $projectData = [
            "name" => "New Test Project",
            "description" => "Description of New Test Project",
            "client_id" => $this->client->id,
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->postJson("{$this->baseUrl}/api/projects", $projectData);

        $response->assertStatus(201);
        $this->assertDatabaseHas('projects', [
            'name' => $projectData['name'],
            'description' => $projectData['description'],
        ]);
    }

    /**
     * @test
     */
    public function it_can_show_a_project()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->get("{$this->baseUrl}/api/projects/{$this->project->id}");

        $response->assertStatus(200)
            ->assertJson([
                'id' => $this->project->id,
                'name' => $this->project->name,
                'description' => $this->project->description,
            ]);
    }

    /**
     * @test
     */
    public function it_can_update_a_project()
    {
        $data = [
            "name" => "Updated Project Name",
            "description" => "Updated project description",
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->putJson("{$this->baseUrl}/api/projects/{$this->project->id}", $data);

        $response->assertStatus(200);
        $this->assertDatabaseHas('projects', [
            'id' => $this->project->id,
            'name' => $data['name'],
            'description' => $data['description'],
        ]);
    }

    /**
     * @test
     */
    public function it_can_delete_a_project()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->delete("{$this->baseUrl}/api/projects/{$this->project->id}");

        $response->assertStatus(204);

        $deletedProject = Project::find($this->project->id);
        $this->assertNull($deletedProject, 'Failed to delete project from database.');
    }
}
