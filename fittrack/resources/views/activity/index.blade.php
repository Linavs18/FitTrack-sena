@extends('templates.base')
@section('title', 'Actividades')
@section('header', 'Actividades')

@section('content')

<label class="fs-2 text-primary">Lista de Actividades</label>

<div class="row mb-4">
    <div class="col-md-6">
        <a href="{{ route('activity.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Crear Actividad
        </a>
    </div>
    <div class="col-md-6">
        <form action="{{ route('activity.index') }}" method="GET">
            <div class="input-group">
                <select name="type_activity" class="form-control">
                    <option value="">Todas las actividades</option>
                    @foreach($activityTypes as $type)
                        <option value="{{ $type }}" {{ request('type_activity') == $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
                <button class="btn btn-secondary" type="submit"><i class="fas fa-filter"></i> Filtrar</button>
            </div>
        </form>
    </div>
</div>

<div class="row">
    @forelse ($activities as $activity)
        @php
            $activityIcons = [
                'Caminata' => 'fas fa-walking',
                'Carrera' => 'fas fa-running',
                'Ciclismo' => 'fas fa-biking',
                'Natación' => 'fas fa-swimmer',
                'Gimnasio' => 'fas fa-dumbbell',
                'Otro' => 'fas fa-question-circle',
            ];
            $icon = $activityIcons[$activity->type_activity] ?? 'fas fa-question-circle';
        @endphp
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card activity-card h-100">
                <div class="card-header activity-card-header">
                    <div class="d-flex align-items-center">
                        <i class="{{ $icon }} fa-2x me-3"></i>
                        <h5 class="mb-0">{{ $activity->type_activity }}</h5>
                    </div>
                    <span class="activity-date">{{ \Carbon\Carbon::parse($activity->date)->format('d/m/Y') }}</span>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span><i class="far fa-clock me-2"></i> Duración</span>
                        @php
                            $hours = floor($activity->duration / 3600);
                            $minutes = floor(($activity->duration % 3600) / 60);
                        @endphp
                        <span class="badge activity-duration-badge">{{ $hours }}h {{ $minutes }}m</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span><i class="fas fa-route me-2"></i> Distancia</span>
                        <strong class="activity-distance">{{ $activity->distance ? number_format($activity->distance, 2) . ' km' : '-' }}</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span><i class="fas fa-fire me-2"></i> Calorías</span>
                        <strong class="activity-calories">{{ $activity->calories ? number_format($activity->calories) . ' cal' : '-' }}</strong>
                    </div>
                </div>
                <div class="card-footer activity-card-footer">
                    <div class="btn-group">
                        <a href="{{ route('activity.edit', $activity->id) }}" class="btn btn-sm btn-edit-activity">
                            <i class="far fa-edit"></i> Editar
                        </a>
                        <button type="button" class="btn btn-sm btn-delete-activity" onclick="confirmDelete({{ $activity->id }})">
                            <i class="fas fa-trash"></i> Eliminar
                        </button>
                    </div>
                    <form id="delete-form-{{ $activity->id }}" action="{{ route('activity.destroy', $activity->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
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
        <div class="stats-card">
            <div class="stats-card-header">
                <h5 class="stats-card-title"><i class="fas fa-chart-line"></i> Resumen de Actividades</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3 stats-item">
                        <div class="stats-number-green">{{ $activities->count() }}</div>
                        <div class="stats-label">Total Actividades</div>
                    </div>
                    <div class="col-md-3 stats-item">
                        @php
                            $totalDuration = $activities->sum('duration');
                            $totalHours = floor($totalDuration / 3600);
                            $totalMinutes = floor(($totalDuration % 3600) / 60);
                        @endphp
                        <div class="stats-number-blue">{{ $totalHours }}h {{ $totalMinutes }}m</div>
                        <div class="stats-label">Tiempo Total</div>
                    </div>
                    <div class="col-md-3 stats-item">
                        <div class="stats-number-green">{{ number_format($activities->whereNotNull('distance')->sum('distance'), 2) }}</div>
                        <div class="stats-label">Kilómetros</div>
                    </div>
                    <div class="col-md-3 stats-item">
                        <div class="stats-number-blue">{{ number_format($activities->whereNotNull('calories')->sum('calories')) }}</div>
                        <div class="stats-label">Calorías</div>
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