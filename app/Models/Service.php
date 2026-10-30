<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'servicios';
    public $timestamps = false;

    protected $fillable = ['nombre', 'precio'];

    protected $casts = ['precio' => 'decimal:2'];

    public function appointments()
    {
        return $this->belongsToMany(Appointment::class, 'citasServicios', 'servicioId', 'citaId');
    }
}
