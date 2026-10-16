<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        $rows = DB::table('waypoints')
            ->whereNotNull('assigned_user_id')
            ->select('id as waypoint_id', 'assigned_user_id as user_id')
            ->get();

        foreach ($rows as $row) {
            DB::table('waypoint_user')->updateOrInsert(
                ['waypoint_id' => (int) $row->waypoint_id, 'user_id' => (int) $row->user_id],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }

    public function down(): void
    {
    }
};

