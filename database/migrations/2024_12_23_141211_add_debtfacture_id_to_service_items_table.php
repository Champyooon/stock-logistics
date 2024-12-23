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
        Schema::table('service_items', function (Blueprint $table) {
            $table->unsignedBigInteger('debtfacture_id')->nullable()->after('id');

            // Définir la clé étrangère
            $table->foreign('debtfacture_id')
                ->references('id')
                ->on('debtfactures') // Remplacez par le nom exact de votre table "debtfactures"
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('service_items', function (Blueprint $table) {
            $table->dropForeign(['debtfacture_id']);
            $table->dropColumn('debtfacture_id');
        });
    }
};
