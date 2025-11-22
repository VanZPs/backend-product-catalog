<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::statement("ALTER TABLE users DROP CONSTRAINT users_role_check");

        DB::statement("ALTER TABLE users 
            ADD CONSTRAINT users_role_check 
            CHECK (role IN ('customer', 'seller', 'admin'))
        ");
    }

    public function down()
    {
        DB::statement("ALTER TABLE users DROP CONSTRAINT users_role_check");

        DB::statement("ALTER TABLE users 
            ADD CONSTRAINT users_role_check 
            CHECK (role IN ('customer', 'seller'))
        ");
    }
};