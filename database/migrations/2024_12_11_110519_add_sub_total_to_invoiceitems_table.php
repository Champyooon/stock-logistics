<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('invoiceitems', function (Blueprint $table) {
            $table->decimal('sub_total', 15, 2)->after('product_quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoiceitems', function (Blueprint $table) {
            $table->dropColumn('sub_total');
        });
    }
};
