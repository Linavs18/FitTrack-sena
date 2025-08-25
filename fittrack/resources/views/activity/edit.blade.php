@extends('templates.base')
@section('title', 'Editar Actividad')
@section('header', 'Editar Actividad')

@section('content')
<div class="container">
    <form action="{{ route('activity.update', $activity->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-3">
            <label for="type_activity" class="form-label">Tipo de Actividad</label>
            <input 
                type="text" 
                name="type_activity" 
                class="form-control" 
                value="{{ old('type_activity', $activity->type_activity) }}" 
                required
                style="text-transform: uppercase;" 
                oninput="this.value = this.value.toUpperCase()"
                list="activities-list">

            <datalist id="activities-list">
                <option value="CORRER"></option>
                <option value="NADAR"></option>
                <option value="CICLISMO"></option>
                <option value="CAMINATA"></option>
                <option value="GIMNASIO"></option>
                <option value="YOGA"></option>
                <option value="OTROS"></option>
            </datalist>

            @error('type_activity') 
                <small class="text-danger">{{ $message }}</small> 
            @enderror
        </div>

        <div class="mb-3">
            <label for="date" class="form-label">Fecha</label>
            <input type="date" name="date" class="form-control" 
                   value="{{ old('date', $activity->date->format('Y-m-d')) }}" required>
            @error('date') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="start_time" class="form-label">Hora de Inicio</label>
                    <input type="time" name="start_time" class="form-control" 
                           value="{{ old('start_time', $start_time) }}" required>
                    @error('start_time') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="end_time" class="form-label">Hora de Fin</label>
                    <input type="time" name="end_time" class="form-control" 
                           value="{{ old('end_time', $end_time) }}" required>
                    @error('end_time') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>
        </div>

        <div class="mb-3">
            <div class="alert alert-info">
                <small>
                    <i class="fas fa-info-circle"></i>
                    <strong>Duración actual:</strong>
                    @php
                        $hours = floor($activity->duration / 3600);
                        $minutes = floor(($activity->duration % 3600) / 60);
                        $seconds = $activity->duration % 60;
                    @endphp
                    {{ sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds) }}
                    ({{ $activity->duration }} segundos)
                </small>
            </div>
        </div>

        <div class="mb-3">
            <label for="distance" class="form-label">Distancia (Km)</label>
            <input type="number" step="0.01" name="distance" class="form-control" 
                   value="{{ old('distance', $activity->distance) }}" 
                   placeholder="Ej: 5.5">
            @error('distance') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="calories" class="form-label">Calorías</label>
            <input type="number" name="calories" class="form-control" 
                   value="{{ old('calories', $activity->calories) }}" 
                   placeholder="Ej: 350">
            @error('calories') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <a href="{{ route('activity.index') }}" class="btn btn-secondary me-md-2">
                <i class="fas fa-arrow-left"></i> Cancelar
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Actualizar Actividad
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Calcular duración en tiempo real
    function calculateDuration() {
        const startTime = document.querySelector('input[name="start_time"]').value;
        const endTime = document.querySelector('input[name="end_time"]').value;
        
        if (startTime && endTime) {
            const start = new Date(`2000-01-01 ${startTime}`);
            const end = new Date(`2000-01-01 ${endTime}`);
            
            if (end > start) {
                const diffMs = end - start;
                const diffSeconds = Math.floor(diffMs / 1000);
                const hours = Math.floor(diffSeconds / 3600);
                const minutes = Math.floor((diffSeconds % 3600) / 60);
                const seconds = diffSeconds % 60;
                
                const durationText = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                
                // Mostrar duración calculada
                document.querySelector('.alert-info small').innerHTML = `
                    <i class="fas fa-clock"></i>
                    <strong>Nueva duración:</strong> ${durationText} (${diffSeconds} segundos)
                `;
            }
        }
    }

    // Agregar event listeners
    document.querySelector('input[name="start_time"]').addEventListener('change', calculateDuration);
    document.querySelector('input[name="end_time"]').addEventListener('change', calculateDuration);
</script>

@if(session('success'))
    <script>Swal.fire('Éxito', "{{ session('success') }}", 'success');</script>
@endif
@if(session('warning'))
    <script>Swal.fire('Atención', "{{ session('warning') }}", 'warning');</script>
@endif
@if(session('error'))
    <script>Swal.fire('Error', "{{ session('error') }}", 'error');</script>
@endif
@endsection