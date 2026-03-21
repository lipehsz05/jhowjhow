<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemVenda extends Model
{
    use HasFactory;
    
    /**
     * A tabela associada ao modelo.
     *
     * @var string
     */
    protected $table = 'itens_venda';
    
    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array
     */
    protected $fillable = [
        'venda_id',
        'produto_id',
        'quantidade',
        'preco_unitario',
        'subtotal',
        'desconto',
        'produto_info',
    ];
    
    /**
     * Os atributos que devem ser convertidos para tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'quantidade' => 'integer',
        'preco_unitario' => 'float',
        'subtotal' => 'float',
        'desconto' => 'float',
        'produto_info' => 'json',
    ];
    
    /**
     * Relacionamento com a venda
     */
    public function venda()
    {
        return $this->belongsTo(Venda::class);
    }
    
    /**
     * Relacionamento com o produto
     */
    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }
}
