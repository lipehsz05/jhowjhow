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
        Schema::table('itens_venda', function (Blueprint $table) {
            // Adicionar coluna produto_info para armazenar dados do produto quando o produto for excluído
            $table->json('produto_info')->nullable();
            
            // Modificar a coluna produto_id para permitir valores nulos
            $table->unsignedBigInteger('produto_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('itens_venda', function (Blueprint $table) {
            // Remover a coluna produto_info
            $table->dropColumn('produto_info');
            
            // Reverter a coluna produto_id para não aceitar valores nulos
            $table->unsignedBigInteger('produto_id')->nullable(false)->change();
        });
    }
};
