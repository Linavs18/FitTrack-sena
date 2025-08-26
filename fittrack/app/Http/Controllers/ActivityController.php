<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ActivityController extends Controller
{
    private $rules = [
        'type_activity' => 'required|string|max:255',
        'date'          => 'required|date',
        'start_time'    => 'required|date_format:H:i',
        'end_time'      => 'required|date_format:H:i|after:start_time',
        'distance'      => 'nullable|numeric|min:0|max:9999.99',
        'calories'      => 'nullable|integer|min:0|max:999999'
    ];

    private $traductionAttributes = [
        'type_activity' => 'Tipo de actividad',
        'date'          => 'Fecha',
        'start_time'    => 'Hora de inicio',
        'end_time'      => 'Hora de fin',
        'distance'      => 'Distancia',
        'calories'      => 'Calorías',
    ];

    private $messages = [
        'required'          => 'El campo :attribute es obligatorio.',
        'numeric'           => 'El campo :attribute debe ser numérico.',
        'date'              => 'El campo :attribute debe ser una fecha válida.',
        'date_format'       => 'El campo :attribute debe tener el formato HH:MM.',
        'after'             => 'El campo :attribute debe ser posterior a la hora de inicio.'
    ];

    public function index(Request $request)
    {
        $query = Activity::where('user_id', auth()->id());

        if ($request->has('type_activity') && $request->type_activity != '') {
            $query->where('type_activity', $request->type_activity);
        }

        $activities = $query->get();
        $activityTypes = Activity::where('user_id', auth()->id())->distinct()->pluck('type_activity');

        return view('activity.index', compact('activities', 'activityTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('activity.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        $validator->setAttributeNames($this->traductionAttributes);

        if ($validator->fails()) {
            return redirect()->route('activity.create')
                ->withInput()
                ->withErrors($validator);
        }

        // Crear las fechas y horas completas usando Carbon
        $startDateTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $request->date . ' ' . $request->start_time);
        $endDateTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $request->date . ' ' . $request->end_time);

        // Calcular duración en segundos usando Carbon
        $duration = $endDateTime->diffInSeconds($startDateTime);

        if ($duration <= 0) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['end_time' => 'La hora de fin debe ser posterior a la hora de inicio.']);
        }

        Activity::create([
            'user_id'       => auth()->id(),
            'type_activity' => strtoupper($request->type_activity),
            'date'          => $request->date,
            'duration'      => $duration,
            'distance'      => $request->distance ?: null,
            'calories'      => $request->calories ?: null,
        ]);

        return redirect()->route('activity.index')
            ->with('success', 'Actividad creada exitosamente');
    }

    /**
     * Mostrar formulario de edición
     */
    public function edit(string $id)
    {
        $activity = Activity::findOrFail($id);

        // Simular horas de inicio y fin basadas en la duración
        $hours = floor($activity->duration / 3600);
        $minutes = floor(($activity->duration % 3600) / 60);

        $start_time = '08:00'; // Hora inicial por defecto
        $end_hour = 8 + $hours;
        $end_minute = $minutes;
        
        // Ajustar si los minutos sobrepasan 60
        if ($end_minute >= 60) {
            $end_hour += floor($end_minute / 60);
            $end_minute = $end_minute % 60;
        }
        
        $end_time = sprintf('%02d:%02d', $end_hour, $end_minute);

        return view('activity.edit', compact('activity', 'start_time', 'end_time'));
    }

    /**
     * Actualizar actividad
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        $validator->setAttributeNames($this->traductionAttributes);

        if ($validator->fails()) {
            return redirect()->route('activity.edit', $id)
                ->withInput()
                ->withErrors($validator);
        }

        $activity = Activity::findOrFail($id);

        // Calcular duración usando Carbon
        $startDateTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $request->date . ' ' . $request->start_time);
        $endDateTime = \Carbon\Carbon::createFromFormat('Y-m-d H:i', $request->date . ' ' . $request->end_time);

        $duration = $endDateTime->diffInSeconds($startDateTime);

        if ($duration <= 0) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['end_time' => 'La hora de fin debe ser posterior a la hora de inicio.']);
        }

        $activity->update([
            'type_activity' => strtoupper($request->type_activity),
            'date'          => $request->date,
            'duration'      => $duration,               // Solo actualizamos la duración
            'distance'      => $request->distance ?: null,
            'calories'      => $request->calories ?: null,
        ]);

        return redirect()->route('activity.index')
            ->with('success', 'Actividad actualizada exitosamente');
    }

    /**
     * Eliminar actividad
     */
    public function destroy(string $id)
    {
        Activity::destroy($id);
        return redirect()->route('activity.index')
            ->with('success', 'La actividad eliminada correctamente');
    }
}