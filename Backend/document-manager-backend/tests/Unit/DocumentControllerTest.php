<?php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\Category;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DocumentControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Run the database migrations
        $this->artisan('migrate');
    }


    /** @test */
    public function it_can_get_documents_by_category()
    {
        Sanctum::actingAs(User::factory()->create(), ['*']);

        $category = Category::factory()->create();
        Document::factory()->count(3)->create(['category_id' => $category->id]);

        $response = $this->getJson("/api/documents/category/{$category->id}");

        $response->assertStatus(200);
        $response->assertJsonCount(3);
    }
}
