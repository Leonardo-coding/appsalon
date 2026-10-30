<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Usuarios de prueba (el modelo aplica bcrypt a la contraseña)
        User::create([
            'nombre' => 'Admin', 'apellido' => 'AppSalon',
            'email' => 'admin@appsalon.com', 'password' => 'Admin123',
            'telefono' => '3000000001', 'admin' => true, 'confirmado' => true,
        ]);

        $cliente = User::create([
            'nombre' => 'Cliente', 'apellido' => 'Prueba',
            'email' => 'cliente@appsalon.com', 'password' => 'Cliente123',
            'telefono' => '3000000002', 'admin' => false, 'confirmado' => true,
        ]);

        // Servicios (puedes cambiar nombres y precios)
        $servicios = [
            ['nombre' => 'Corte de cabello', 'precio' => 30.00],
            ['nombre' => 'Tinte de cabello', 'precio' => 80.00],
            ['nombre' => 'Manicure',         'precio' => 25.00],
            ['nombre' => 'Pedicure',         'precio' => 35.00],
            ['nombre' => 'Peinado',          'precio' => 40.00],
            ['nombre' => 'Tratamiento capilar', 'precio' => 55.00],
        ];
        foreach ($servicios as $s) {
            Service::create($s);
        }

        // Una cita de ejemplo con dos servicios, para probar las relaciones
        $cita = Appointment::create([
            'fecha' => now()->addDay()->toDateString(),
            'hora' => '10:00:00',
            'usuarioId' => $cliente->id,
        ]);
        $cita->services()->attach(Service::whereIn('nombre', ['Corte de cabello', 'Manicure'])->pluck('id'));
    }
}
