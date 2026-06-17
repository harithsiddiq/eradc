<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->string('video_file_path')->nullable()->after('video_url');
        });

        // Also update the video_provider enum to include 'upload'
        // We can handle this by just allowing any string since it's a varchar
        DB::statement("ALTER TABLE lessons MODIFY COLUMN video_provider ENUM('hls','youtube','vimeo','direct','upload') NULL");
    }

    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn('video_file_path');
        });

        DB::statement("ALTER TABLE lessons MODIFY COLUMN video_provider ENUM('hls','youtube','vimeo','direct') NULL");
    }
};
