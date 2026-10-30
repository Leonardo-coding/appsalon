<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    // La tabla se llama "usuarios" y no sigue la convención de Laravel
    protected $table = 'usuarios';
    public $timestamps = false;

    protected $fillable = [
        'nombre', 'apellido', 'email', 'password',
        'telefono', 'admin', 'confirmado', 'token',
    ];

    protected $hidden = ['password', 'token'];

    protected function casts(): array
    {
        return [
            'password'   => 'hashed',   // Laravel aplica bcrypt automáticamente
            'admin'      => 'boolean',
            'confirmado' => 'boolean',
        ];
    }

    // La tabla no tiene columna remember_token
    public function getRememberTokenName()
    {
        return '';
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'usuarioId');
    }
}
