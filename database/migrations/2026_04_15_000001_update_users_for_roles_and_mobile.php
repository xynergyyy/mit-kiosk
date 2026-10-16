<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY role VARCHAR(50) NOT NULL DEFAULT 'faculty'");
        }

        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'availability_status')) {
                $table->string('availability_status', 20)->default('available')->after('role');
            }
            if (!Schema::hasColumn('users', 'api_token_hash')) {
                $table->string('api_token_hash', 64)->nullable()->unique()->after('remember_token');
            }
            if (!Schema::hasColumn('users', 'api_token_expires_at')) {
                $table->timestamp('api_token_expires_at')->nullable()->after('api_token_hash');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'api_token_expires_at')) {
                $table->dropColumn('api_token_expires_at');
            }
            if (Schema::hasColumn('users', 'api_token_hash')) {
                $table->dropUnique(['api_token_hash']);
                $table->dropColumn('api_token_hash');
            }
            if (Schema::hasColumn('users', 'availability_status')) {
                $table->dropColumn('availability_status');
            }
        });
    }
};

