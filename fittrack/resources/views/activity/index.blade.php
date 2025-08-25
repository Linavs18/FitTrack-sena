@extends('templates.base')
@section('title', 'Actividades')
@section('header', 'Actividades')

@section('content')
<label class="fs-2 text-success">Lista de Actividades</label>

<div class="row">
    <div class="col-lg-12 mb-4 d-grid gap-2 d-md-block">
        <a href="{{ route('activity.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Crear Actividad
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-12 mb-4">
        <table id="table_data" class="table table-striped align-items-center text-center text-white">
            <thead style="background: linear-gradient(135deg, #4CAF50, #2196F3); color: white;">
                <tr>
                    <th style="color: white">Id</th>
                    <th style="color: white">Tipo de Actividad</th>
                    <th style="color: white">Fecha</th>
                    <th style="color: white">Duración</th>
                    <th style="color: white">Distancia</th>
                    <th style="color: white">Calorías</th>
                    <th style="color: white">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($activities as $activity)
                <tr>
                    <td><strong style="color: #2196F3;">{{ $activity->id }}</strong></td>
                    <td>
                        <span class="badge" style="background-color: #4CAF50; color: white;">
                            {{ $activity->type_activity }}
                        </span>
                    </td>
                    <td>{{ \Carbon\Carbon::parse($activity->date)->format('d/m/Y') }}</td>
                    <td>
                        @php
                            $hours = floor($activity->duration / 3600);
                            $minutes = floor(($activity->duration % 3600) / 60);
                            $seconds = $activity->duration % 60;
                        @endphp
                        <span class="badge" style="background-color: #2196F3; color: white;">
                            {{ sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds) }}
                        </span>
                    </td>
                    <td>
                        @if($activity->distance)
                            <strong style="color: #4CAF50;">{{ number_format($activity->distance, 2) }}</strong> 
                            <small class="text-muted">km</small>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($activity->calories)
                            <strong style="color: #2196F3;">{{ number_format($activity->calories) }}</strong> 
                            <small class="text-muted">cal</small>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('activity.edit', $activity->id) }}" 
                               class="btn btn-success btn-sm"
                               title="Editar">
                                <i class="far fa-edit"></i>
                            </a>

                            <button type="button" 
                                    class="btn btn-danger btn-sm" 
                                    onclick="confirmDelete({{ $activity->id }})"
                                    title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>

                        <form id="delete-form-{{ $activity->id }}" 
                              action="{{ route('activity.destroy', $activity->id) }}" 
                              method="POST" 
                              style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($activities->isEmpty())
            <div class="text-center py-5">
                <div class="alert" style="background: linear-gradient(135deg, #E8F5E8, #E3F2FD); border: 1px solid #4CAF50;">
                    <i class="fas fa-info-circle fa-2x mb-3" style="color: #4CAF50;"></i>
                    <h4 style="color: #2196F3;">No hay actividades registradas</h4>
                    <p class="mb-3">Comienza creando tu primera actividad deportiva para llevar un registro de tu progreso.</p>
                    <a href="{{ route('activity.create') }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> Crear Primera Actividad
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

{{-- Resumen estadístico --}}
@if($activities->count() > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="card" style="border: 2px solid #4CAF50;">
            <div class="card-header" style="background: linear-gradient(135deg, #4CAF50, #2196F3);">
                <h5 class="card-title mb-0 text-white">
                    <i class="fas fa-chart-bar"></i> Resumen de Actividades
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="border-end" style="border-color: #4CAF50 !important;">
                            <h3 style="color: #4CAF50;">{{ $activities->count() }}</h3>
                            <small class="text-muted">Total Actividades</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border-end" style="border-color: #2196F3 !important;">
                            @php
                                $totalDuration = $activities->sum('duration');
                                $totalHours = floor($totalDuration / 3600);
                                $totalMinutes = floor(($totalDuration % 3600) / 60);
                            @endphp
                            <h3 style="color: #2196F3;">{{ $totalHours }}h {{ $totalMinutes }}m</h3>
                            <small class="text-muted">Tiempo Total</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="border-end" style="border-color: #4CAF50 !important;">
                            <h3 style="color: #4CAF50;">{{ number_format($activities->whereNotNull('distance')->sum('distance'), 2) }}</h3>
                            <small class="text-muted">Kilómetros</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <h3 style="color: #2196F3;">{{ number_format($activities->whereNotNull('calories')->sum('calories')) }}</h3>
                        <small class="text-muted">Calorías</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Confirmar eliminación
    function confirmDelete(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "No podrás revertir esta acción",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-form-${id}`).submit();
            }
        })
    }

    // Mostrar mensajes flash con SweetAlert
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: "{{ session('success') }}",
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
    @endif
    @if(session('warning'))
        Swal.fire({
            icon: 'warning',
            title: 'Atención',
            text: "{{ session('warning') }}",
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
    @endif
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: "{{ session('error') }}",
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
    @endif
</script>
@endsection