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
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vehicule_id')->nullable();
            $table->unsignedBigInteger('material_id')->nullable();
            $table->unsignedBigInteger('inventory_id')->nullable();
            $table->string('type_maintenance'); // Type de maintenance
            $table->date('date_debut'); // Date de début de l’intervention
            $table->date('date_fin')->nullable(); // Date de fin
            $table->string('responsable'); // Responsable
            $table->enum('statut', ['En cours', 'Terminée']); // Statut de la maintenance
            $table->text('probleme_detecte')->nullable(); // Problème détecté
            $table->text('action_effectuee')->nullable(); // Action effectuée
            $table->decimal('cout_total', 10, 2)->nullable(); // Coût total
            $table->timestamps();

             // Définir les relations (clés étrangères)
             $table->foreign('vehicule_id')->references('id')->on('vehicules')->onDelete('cascade');
             $table->foreign('material_id')->references('id')->on('materials')->onDelete('cascade');
             $table->foreign('inventory_id')->references('id')->on('inventories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
