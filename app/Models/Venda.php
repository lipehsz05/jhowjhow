<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venda extends Model
{
    use HasFactory;
    
    /**
     * A tabela associada ao modelo.
     *
     * @var string
     */
    protected $table = 'vendas';
    
    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array
     */
    protected $fillable = [
        'cliente_id',
        'usuario_id',
        'data',
        'valor_total',
        'desconto',
        'forma_pagamento',
        'status',
        'observacao',
        'codigo',
    ];
    
    /**
     * Os atributos que devem ser convertidos para tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'datetime',
        'valor_total' => 'float',
        'desconto' => 'float',
    ];
    
    /**
     * Relacionamento com o cliente
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
    
    /**
     * Relacionamento com o usuário que registrou a venda
     */
    public function usuario()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Relacionamento com os itens da venda
     */
    public function itens()
    {
        return $this->hasMany(ItemVenda::class);
    }
}
