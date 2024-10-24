<?php
namespace Tests\Feature;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function test_can_get_categories()
    {
        // Create an admin user
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        // Authenticate using Sanctum
        Sanctum::actingAs($admin, ['*']);

        // Create categories using the factory
        Category::factory()->count(3)->create();

        // Make a GET request to the categories API
        $response = $this->getJson('/api/categories');

        // Assert the status and JSON count
        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }

    /** @test */
    public function it_can_create_a_category()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin, 'sanctum');

        $categoryData = [
            'name' => 'New Category',
            'description' => 'This is a test category',
        ];

        $response = $this->postJson('/api/categories', $categoryData);

        $response->assertStatus(201);
        $this->assertDatabaseHas('categories', ['name' => 'New Category']);
    }

    /** @test */
    public function it_can_show_a_single_category()



    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        // Authenticate using Sanctum
        Sanctum::actingAs($admin, ['*']);

        $category = Category::factory()->create();

        $response = $this->getJson("/api/categories/{$category->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => $category->name]);
    }

    /** @test */
    public function it_can_update_a_category()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin, 'sanctum');

        $category = Category::factory()->create();

        $updateData = [
            'name' => 'Updated Category',
            'description' => 'Updated description',
        ];

        $response = $this->putJson("/api/categories/{$category->id}", $updateData);

        $response->assertStatus(200);
        $this->assertDatabaseHas('categories', ['name' => 'Updated Category']);
    }

    /** @test */
    public function it_can_delete_a_category()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin, 'sanctum');

        $category = Category::factory()->create();

        $response = $this->deleteJson("/api/categories/{$category->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    /** @test */
    public function it_requires_name_and_description_to_create_a_category()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin, 'sanctum');

        $response = $this->postJson('/api/categories', []);

        $response->assertStatus(422); // Unprocessable Entity
        $response->assertJsonValidationErrors(['name', 'description']);
    }
}
