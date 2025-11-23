<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->string('link', 255)->nullable()->after('slug');
            $table->boolean('show_in_menu')->default(true)->after('link');
            $table->integer('order')->default(0)->after('show_in_menu');
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropColumn(['link', 'show_in_menu', 'order']);
        });
    }
};
