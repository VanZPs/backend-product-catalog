<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Check DB driver: SQLite (in-memory tests) may not support DROP COLUMN reliably.
        // To avoid failing tests, skip dropping the user_id column on sqlite.
        $driver = DB::getDriverName();
        if ($driver !== 'sqlite' && Schema::hasColumn('reviews', 'user_id')) {
            Schema::table('reviews', function (Blueprint $table) {
                $table->dropColumn('user_id');
            });
        }
        
        // Add reviewer fields if they don't exist
        Schema::table('reviews', function (Blueprint $table) {
            if (!Schema::hasColumn('reviews', 'name')) {
                $table->string('name')->nullable(); // reviewer name
            }
            if (!Schema::hasColumn('reviews', 'email')) {
                $table->string('email')->nullable(); // reviewer email
            }
            if (!Schema::hasColumn('reviews', 'province_id')) {
                $table->string('province_id', 10)->nullable(); // valid dropdown Laravolt Indonesia
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
