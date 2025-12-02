<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Auth\Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class User extends Model implements AuthenticatableContract
{
    use Notifiable, Authenticatable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Constantes para roles
    const ROLE_ADMIN = 'administrador';
    const ROLE_BARBERO = 'barbero';
    const ROLE_CLIENTE = 'cliente';

    // Métodos helper para verificar roles
    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isBarbero()
    {
        return $this->role === self::ROLE_BARBERO;
    }

    public function isCliente()
    {
        return $this->role === self::ROLE_CLIENTE;
    }

    // Scope para filtrar por rol
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }
}