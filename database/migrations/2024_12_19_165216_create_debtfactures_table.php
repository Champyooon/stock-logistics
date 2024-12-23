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
        Schema::create('debtfactures', function (Blueprint $table) {
            $table->id(); // ID primaire
            $table->foreignId('client_id')->nullable()->constrained('clients')->cascadeOnDelete(); // Clé étrangère vers 'clients'
            $table->foreignId('invoice_id')->nullable()->constrained('invoices')->cascadeOnDelete(); // Clé étrangère vers 'invoices'
            $table->date('date_invoice'); // Date de la facture
            $table->decimal('total_price', 10, 2)->nullable(); // Montant total
            $table->timestamps(); // Colonnes created_at et updated_at
        });
    }

    /** 
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('debtfactures');
    }
};
