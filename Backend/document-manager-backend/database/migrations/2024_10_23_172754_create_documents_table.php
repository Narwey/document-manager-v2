<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Document title
            $table->string('file_path'); // S3/MinIO file path
            $table->string('file_type'); // Example: PDF, DOCX, TXT, etc.
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Linked to users
            $table->foreignId('category_id')->constrained()->onDelete('cascade'); // Linked to categories
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
