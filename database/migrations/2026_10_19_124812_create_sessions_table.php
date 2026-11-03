<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    // sessions already ships inside 2026_10_13_010100_create_users_table.php with
    // a uuid user_id, so this duplicate would collide on a fresh migrate.
    public function up(): void
    {
        //
    }

    public function down(): void
    {
        //
    }
};
