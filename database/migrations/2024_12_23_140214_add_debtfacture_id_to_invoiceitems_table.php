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
            $table->unsignedBigInteger('debtfacture_id')->nullable()->after('id');

            $table->foreign('debtfacture_id')
                ->references('id')
                ->on('debtfactures')
                ->onDelete('set null'); // Si la facture est supprimée, la colonne est mise à null
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoiceitems', function (Blueprint $table) {
             // Supprimer la contrainte de clé étrangère
             $table->dropForeign(['debtfacture_id']);

             // Supprimer la colonne debtfacture_id
             $table->dropColumn('debtfacture_id');
        });
    }
};
