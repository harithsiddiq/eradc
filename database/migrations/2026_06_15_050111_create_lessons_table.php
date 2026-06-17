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
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->string('slug');
            $table->json('title');
            $table->json('description');
            $table->string('video_url', 500)->nullable();
            $table->enum('video_provider', ['hls', 'youtube', 'vimeo', 'direct'])->default('hls');
            $table->integer('duration_seconds')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_published')->default(false);
            $table->boolean('is_preview')->default(false);
            $table->timestamps();
            $table->unique(['course_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
