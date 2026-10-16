<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('waypoint_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('waypoint_id')->constrained('waypoints')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['waypoint_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('waypoint_user');
    }
};

