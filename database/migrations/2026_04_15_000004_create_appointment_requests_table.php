<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointment_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('target_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('waypoint_id')->nullable()->constrained('waypoints')->nullOnDelete();

            $table->string('requester_type', 20);
            $table->string('requester_name');
            $table->string('grade_level', 20)->nullable();
            $table->string('requester_email');

            $table->string('purpose_code', 100);
            $table->text('purpose_other_text')->nullable();

            $table->string('status', 20)->default('pending');
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('responded_at')->nullable();

            $table->timestamps();

            $table->index(['target_user_id', 'status']);
            $table->index(['waypoint_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointment_requests');
    }
};

