<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $table = 'citas';
    public $timestamps = false;

    protected $fillable = ['fecha', 'hora', 'usuarioId'];

    public function user()
    {
        return $this->belongsTo(User::class, 'usuarioId');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'citasServicios', 'citaId', 'servicioId');
    }
}