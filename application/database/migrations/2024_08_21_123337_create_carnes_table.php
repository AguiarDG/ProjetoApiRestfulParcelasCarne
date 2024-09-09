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
        Schema::create('carnes', function (Blueprint $table) {
            $table->bigIncrements('id_carne')->primary();
            $table->float('valor_total', 8, 2); // FLOAT(12) NOT NULL (8, 2 são placeholders para dígitos totais e decimais)
            $table->integer('qtd_parcelas'); // INT(10,0) NOT NULL
            $table->date('data_primeiro_vencimento'); // DATE NOT NULL
            $table->string('periodicidade', 100)->default(''); // VARCHAR(100) NOT NULL DEFAULT ''
            $table->timestamps(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carnes');
    }
};
