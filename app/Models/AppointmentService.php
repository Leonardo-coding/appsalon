<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppointmentService extends Model
{
    protected $table = 'citasServicios';
    public $timestamps = false;

    protected $fillable = ['citaId', 'servicioId'];
}