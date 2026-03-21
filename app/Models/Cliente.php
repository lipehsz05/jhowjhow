<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;
    
    /**
     * A tabela associada ao modelo.
     *
     * @var string
     */
    protected $table = 'clientes';
    
    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array
     */
    protected $fillable = [
        'nome',
        'email',
        'telefone',
        'cpf_cnpj',
        'endereco',
        'cidade',
        'estado',
        'cep',
        'data_cadastro',
        'data_ultima_compra',
        'valor_ultima_compra'
    ];
    
    /**
     * Os atributos que devem ser convertidos para tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'data_cadastro' => 'datetime',
        'data_ultima_compra' => 'datetime',
        'valor_ultima_compra' => 'float',
    ];
    
    /**
     * Relacionamento com as vendas do cliente
     */
    public function vendas()
    {
        return $this->hasMany(Venda::class);
    }
}
