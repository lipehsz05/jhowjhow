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
        // Atualizando a tabela de usuários para incluir campos adicionais
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->after('name');
            $table->string('nivel_acesso')->default('usuario')->after('password');
            $table->timestamp('ultimo_acesso')->nullable()->after('nivel_acesso');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'nivel_acesso', 'ultimo_acesso']);
        });
    }
};
