<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Document;
use App\Models\User;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DocumentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_belongs_to_a_user()
    {
        $user = User::factory()->create();
        $document = Document::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $document->user);
    }

    /** @test */
    public function it_belongs_to_a_category()
    {
        $category = Category::factory()->create();
        $document = Document::factory()->create(['category_id' => $category->id]);

        $this->assertInstanceOf(Category::class, $document->category);
    }
}
