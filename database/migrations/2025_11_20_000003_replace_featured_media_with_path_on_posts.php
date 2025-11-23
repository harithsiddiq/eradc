<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->string('featured_image_path', 512)->nullable()->after('author_id');
            $table->dropConstrainedForeignId('featured_media_id');
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->foreignId('featured_media_id')->nullable()->constrained('media')->nullOnDelete();
            $table->dropColumn('featured_image_path');
        });
    }
};
