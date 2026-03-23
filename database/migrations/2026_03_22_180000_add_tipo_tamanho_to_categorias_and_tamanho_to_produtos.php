<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categorias', function (Blueprint $table) {
            $table->string('tipo_tamanho', 20)->default('unico')->after('descricao');
        });

        Schema::table('produtos', function (Blueprint $table) {
            $table->string('tamanho', 20)->nullable()->after('categoria_id');
        });
    }

    public function down(): void
    {
        Schema::table('produtos', function (Blueprint $table) {
            $table->dropColumn('tamanho');
        });

        Schema::table('categorias', function (Blueprint $table) {
            $table->dropColumn('tipo_tamanho');
        });
    }
};
