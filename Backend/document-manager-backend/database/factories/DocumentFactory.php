<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentFactory extends Factory
{
    protected $model = Document::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence(3),
            'file_path' => 'uploads/' . $this->faker->word() . '.pdf', // Example file path
            'file_type' => 'pdf',
            'user_id' => User::factory(), // Creates a user for the document
            'category_id' => Category::factory(), // Creates a category for the document
        ];
    }
}

