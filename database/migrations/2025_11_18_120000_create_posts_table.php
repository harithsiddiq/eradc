<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->json('title');
            $table->string('slug', 255)->unique();
            $table->json('content');
            $table->json('excerpt')->nullable();
            $table->enum('status', ['draft', 'published', 'archived']);
            $table->foreignId('author_id')->constrained('users');
            $table->foreignId('featured_media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
