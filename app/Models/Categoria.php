<?php

namespace App\Models;

use App\Support\TamanhosBrasil;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    use HasFactory;
    
    /**
     * A tabela associada ao modelo.
     *
     * @var string
     */
    protected $table = 'categorias';
    
    /**
     * Os atributos que são atribuíveis em massa.
     *
     * @var array
     */
    protected $fillable = [
        'nome',
        'descricao',
        'tipo_tamanho',
        'ativa',
    ];
    
    /**
     * Os atributos que devem ser convertidos para tipos nativos.
     *
     * @var array
     */
    protected $casts = [
        'ativa' => 'boolean',
    ];
    
    /**
     * Relacionamento com produtos da categoria
     */
    public function produtos()
    {
        return $this->hasMany(Produto::class);
    }

    public function getTipoTamanhoLabelAttribute(): string
    {
        $labels = TamanhosBrasil::labelsTipo();

        return $labels[$this->tipo_tamanho ?? TamanhosBrasil::TIPO_UNICO] ?? (string) $this->tipo_tamanho;
    }
}
