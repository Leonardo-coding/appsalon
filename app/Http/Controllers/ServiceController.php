<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceRequest;
use App\Models\Service;
use Illuminate\Database\QueryException;

class ServiceController extends Controller
{
    // Catálogo público
    public function index()
    {
        $servicios = Service::orderBy('nombre')->get();

        return view('servicios.index', compact('servicios'));
    }

    public function create()
    {
        return view('servicios.form');
    }

    public function store(ServiceRequest $request)
    {
        Service::create($request->validated());

        return redirect()->route('servicios.index')->with('exito', 'Servicio creado correctamente.');
    }

    public function edit(Service $servicio)
    {
        return view('servicios.form', compact('servicio'));
    }

    public function update(ServiceRequest $request, Service $servicio)
    {
        $servicio->update($request->validated());

        return redirect()->route('servicios.index')->with('exito', 'Servicio actualizado correctamente.');
    }

    public function destroy(Service $servicio)
    {
        // Integridad referencial: no se borra un servicio que ya tiene citas
        if ($servicio->appointments()->exists()) {
            return redirect()->route('servicios.index')
                ->with('error', 'No se puede eliminar "' . $servicio->nombre . '" porque tiene citas asociadas.');
        }

        try {
            $servicio->delete();
        } catch (QueryException $e) {
            return redirect()->route('servicios.index')
                ->with('error', 'No se pudo eliminar el servicio. Inténtalo de nuevo.');
        }

        return redirect()->route('servicios.index')->with('exito', 'Servicio eliminado correctamente.');
    }
}
