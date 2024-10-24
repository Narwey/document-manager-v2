<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Category;
use App\Models\Document;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_category_can_have_many_documents()
    {
        $category = Category::factory()->create();
        Document::factory()->count(3)->create(['category_id' => $category->id]);

        $this->assertCount(3, $category->documents);
    }
}

