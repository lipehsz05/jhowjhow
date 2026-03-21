<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'ultimo_acesso',
        'nivel_acesso',
        'last_activity',
        'is_online'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_activity' => 'datetime',
            'is_online' => 'boolean',
        ];
    }
    
    /**
     * Verifica se o usuário está online
     * Considera online se a última atividade foi há menos de 5 minutos
     */
    public function isOnline()
    {
        if ($this->is_online && $this->last_activity) {
            return $this->last_activity->diffInMinutes(now()) < 5;
        }
        return false;
    }
    
    /**
     * Verifica se o usuário é um administrador
     * 
     * @return bool
     */
    public function isAdmin()
    {
        return $this->nivel_acesso === 'admin';
    }
    
    /**
     * Verifica se o usuário é um vendedor
     * 
     * @return bool
     */
    public function isVendedor()
    {
        return $this->nivel_acesso === 'vendedor';
    }
    
    /**
     * Verifica se o usuário é um estoquista
     * 
     * @return bool
     */
    public function isEstoquista()
    {
        return $this->nivel_acesso === 'estoquista';
    }
}
