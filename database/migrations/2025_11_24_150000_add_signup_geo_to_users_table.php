<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('signup_ip')->nullable()->after('level');
            $table->string('signup_country')->nullable()->after('signup_ip');
            $table->string('signup_region')->nullable()->after('signup_country');
            $table->string('signup_city')->nullable()->after('signup_region');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['signup_ip', 'signup_country', 'signup_region', 'signup_city']);
        });
    }
};

