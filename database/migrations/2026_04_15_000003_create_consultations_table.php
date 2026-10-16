<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('consultations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assigned_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('waypoint_id')->nullable()->constrained('waypoints')->nullOnDelete();
            $table->unsignedInteger('ticket_number');
            $table->string('purpose_code', 100);
            $table->text('purpose_other_text')->nullable();
            $table->string('status', 20)->default('waiting');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['assigned_user_id', 'status']);
            $table->index(['waypoint_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultations');
    }
};

