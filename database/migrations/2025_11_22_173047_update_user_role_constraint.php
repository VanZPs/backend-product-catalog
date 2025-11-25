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
        $driver = DB::getDriverName();
        if ($driver !== 'sqlite') {
            DB::statement("ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check");

            DB::statement("ALTER TABLE users 
                ADD CONSTRAINT users_role_check 
                CHECK (role IN ('customer', 'seller', 'admin'))
            ");
        }
    }

    public function down()
    {
        $driver = DB::getDriverName();
        if ($driver !== 'sqlite') {
            DB::statement("ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check");

            DB::statement("ALTER TABLE users 
                ADD CONSTRAINT users_role_check 
                CHECK (role IN ('customer', 'seller'))
            ");
        }
    }
};