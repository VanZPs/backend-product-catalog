<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * We'll create indexes if they don't exist (Postgres/SQLite friendly using IF NOT EXISTS where supported).
     */
    public function up()
    {
        // products.seller_id
        try {
            DB::statement("CREATE INDEX IF NOT EXISTS products_seller_id_idx ON products (seller_id);");
        } catch (\Exception $e) {
            // ignore if not supported
        }

        // sellers.province_id, sellers.city_id, sellers.store_name
        try {
            DB::statement("CREATE INDEX IF NOT EXISTS sellers_province_id_idx ON sellers (province_id);");
            DB::statement("CREATE INDEX IF NOT EXISTS sellers_city_id_idx ON sellers (city_id);");
            DB::statement("CREATE INDEX IF NOT EXISTS sellers_store_name_idx ON sellers (store_name);");
        } catch (\Exception $e) {
            // ignore
        }

        // reviews.product_id
        try {
            DB::statement("CREATE INDEX IF NOT EXISTS reviews_product_id_idx ON reviews (product_id);");
        } catch (\Exception $e) {
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        try { DB::statement("DROP INDEX IF EXISTS products_seller_id_idx;"); } catch (\Exception$e) {}
        try { DB::statement("DROP INDEX IF EXISTS sellers_province_id_idx;"); } catch (\Exception$e) {}
        try { DB::statement("DROP INDEX IF EXISTS sellers_city_id_idx;"); } catch (\Exception$e) {}
        try { DB::statement("DROP INDEX IF EXISTS sellers_store_name_idx;"); } catch (\Exception$e) {}
        try { DB::statement("DROP INDEX IF EXISTS reviews_product_id_idx;"); } catch (\Exception$e) {}
    }
};
