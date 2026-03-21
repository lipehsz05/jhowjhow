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
        Schema::create('movimentacoes_estoque', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produto_id')->constrained('produtos');
            $table->foreignId('user_id')->constrained('users');
            $table->enum('tipo', ['entrada', 'saida']);
            $table->integer('quantidade');
            $table->dateTime('data');
            $table->string('motivo'); // compra, venda, ajuste, devolução, etc
            $table->text('observacao')->nullable();
            $table->string('documento_referencia')->nullable(); // número da nota fiscal, id de venda, etc
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimentacoes_estoque');
    }
};
