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
        Schema::create('carne_parcelas', function (Blueprint $table) {
            $table->bigIncrements('id_carne_parcela')->primary();
            $table->unsignedBigInteger('id_carne'); // INT(10,0) NOT NULL
            $table->integer('numero'); // INT(10,0) NOT NULL
            $table->float('valor', 8, 2); // FLOAT(12) NOT NULL (8, 2 são placeholders para dígitos totais e decimais)
            $table->date('data_vencimento'); // DATE NOT NULL
            $table->boolean('entrada')->default(false); // TINYINT(1) NOT NULL DEFAULT '0'
            $table->timestamps(0); // equivale a `created_at` e `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP

            // Definindo índice
            $table->index('id_carne', 'carnes_parcelas_id_carne_idx'); // INDEX `carnes_parcelas_id_carne_idx`

            // Definindo chave estrangeira
            $table->foreign('id_carne')
                    ->references('id_carne')
                    ->on('carnes')
                    ->onUpdate('no action')
                    ->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carne_parcelas');
    }
};
