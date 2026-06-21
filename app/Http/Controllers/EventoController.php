<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Localidad;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EventoController extends Controller {
    public function index(Request $request) {
        // Carga la relación para mostrar el nombre en vez del ID en la tabla
        $query = Evento::with('localidad');

        // Filtro por nombre
        if ($request->filled('nombre')) {
            $query->where('nombre_evento', 'like', '%' . $request->nombre . '%');
        }
        // Filtro por localidad
        if ($request->filled('localidad_id')) {
            $query->where('id_localidad', $request->localidad_id);
        }
        // Filtro por fecha de carga
        if ($request->filled('fecha')) {
            $query->whereDate('fecha', $request->fecha);
        }

        return Inertia::render('Eventos/Index', [
            'eventos' => $query->get(),
            'localidades' => Localidad::all(),
            'filtros' => $request->only(['nombre', 'localidad_id', 'fecha'])
        ]);
    }

    public function store(Request $request) {
        $request->validate([
            'nombre_evento' => 'required|string|max:255',
            'fecha' => 'required|date',
            'id_localidad' => 'required|exists:localidads,id',
        ]);

        Evento::create($request->all());
        return redirect()->back();
    }

    public function update(Request $request, Evento $evento) {
        $request->validate([
            'nombre_evento' => 'required|string|max:255',
            'fecha' => 'required|date',
            'id_localidad' => 'required|exists:localidads,id',
        ]);

        $evento->update($request->all());
        return redirect()->back();
    }

    public function destroy(Evento $evento) {
        $evento->delete();
        return redirect()->back();
    }
}