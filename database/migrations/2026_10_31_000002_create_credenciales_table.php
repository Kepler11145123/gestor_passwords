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
        Schema::create('credenciales', function (Blueprint $table) {
            // Clave primaria personalizada de tipo string
            $table->string('id_credencial', 64)->primary();

            // Llave foránea vinculada a la tabla 'categorias'
            $table->foreignId('categoria_id')
                  ->constrained('categorias')
                  ->onDelete('cascade');

            $table->string('titulo', 150);
            $table->string('usuario', 150);
            $table->text('password'); // Campo text para almacenar la cadena encriptada con Crypt (AES-256)
            $table->string('url')->nullable();
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credenciales');
    }
};
