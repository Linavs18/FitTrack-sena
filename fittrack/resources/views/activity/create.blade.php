@extends('templates.base')
@section('title', 'Crear Actividad')
@section('header', 'Crear Actividad')

@section('content')
<div class="container">
    <form action="{{ route('activity.store') }}" method="POST">
        @csrf
        
        <div class="mb-3">
            <label for="type_activity" class="form-label">Tipo de Actividad</label>
            <input 
                type="text" 
                name="type_activity" 
                class="form-control" 
                value="{{ old('type_activity') }}" 
                required
                style="text-transform: uppercase;" 
                oninput="this.value = this.value.toUpperCase()"
                list="activities-list"
                placeholder="Selecciona o escribe un tipo de actividad">

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
                   value="{{ old('date', date('Y-m-d')) }}" required>
            @error('date') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="start_time" class="form-label">Hora de Inicio</label>
                    <input type="time" name="start_time" class="form-control" 
                           value="{{ old('start_time') }}" required>
                    @error('start_time') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="end_time" class="form-label">Hora de Fin</label>
                    <input type="time" name="end_time" class="form-control" 
                           value="{{ old('end_time') }}" required>
                    @error('end_time') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </div>
        </div>

        <div class="mb-3" id="duration-display" style="display: none;">
            <div class="alert alert-success">
                <small>
                    <i class="fas fa-clock"></i>
                    <strong>Duración calculada:</strong>
                    <span id="duration-text">--:--:--</span>
                    (<span id="duration-seconds">0</span> segundos)
                </small>
            </div>
        </div>

        <div class="mb-3">
            <label for="distance" class="form-label">Distancia (Km)</label>
            <input type="number" step="0.01" name="distance" class="form-control" 
                   value="{{ old('distance') }}" 
                   placeholder="Ej: 5.5">
            <small class="form-text text-muted">Opcional - Ingresa la distancia recorrida</small>
            @error('distance') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label for="calories" class="form-label">Calorías</label>
            <input type="number" name="calories" class="form-control" 
                   value="{{ old('calories') }}" 
                   placeholder="Ej: 350">
            <small class="form-text text-muted">Opcional - Calorías quemadas durante la actividad</small>
            @error('calories') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
            <a href="{{ route('activity.index') }}" class="btn btn-secondary me-md-2">
                <i class="fas fa-arrow-left"></i> Cancelar
            </a>
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Guardar Actividad
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Establecer fecha actual por defecto si está vacía
    document.addEventListener('DOMContentLoaded', function() {
        const dateInput = document.querySelector('input[name="date"]');
        if (!dateInput.value) {
            dateInput.value = new Date().toISOString().split('T')[0];
        }
    });

    // Calcular duración en tiempo real
    function calculateDuration() {
        const startTime = document.querySelector('input[name="start_time"]').value;
        const endTime = document.querySelector('input[name="end_time"]').value;
        const durationDisplay = document.getElementById('duration-display');
        const durationText = document.getElementById('duration-text');
        const durationSeconds = document.getElementById('duration-seconds');
        
        if (startTime && endTime) {
            const start = new Date(`2000-01-01 ${startTime}`);
            const end = new Date(`2000-01-01 ${endTime}`);
            
            if (end > start) {
                const diffMs = end - start;
                const diffSecondsTotal = Math.floor(diffMs / 1000);
                const hours = Math.floor(diffSecondsTotal / 3600);
                const minutes = Math.floor((diffSecondsTotal % 3600) / 60);
                const seconds = diffSecondsTotal % 60;
                
                const timeText = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
                
                durationText.textContent = timeText;
                durationSeconds.textContent = diffSecondsTotal;
                durationDisplay.style.display = 'block';
            } else {
                durationDisplay.style.display = 'none';
                
                // Mostrar alerta si la hora de fin es menor que la de inicio
                if (endTime) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Horario inválido',
                        text: 'La hora de fin debe ser posterior a la hora de inicio',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
            }
        } else {
            durationDisplay.style.display = 'none';
        }
    }

    // Agregar event listeners
    document.querySelector('input[name="start_time"]').addEventListener('change', calculateDuration);
    document.querySelector('input[name="end_time"]').addEventListener('change', calculateDuration);
    document.querySelector('input[name="start_time"]').addEventListener('input', calculateDuration);
    document.querySelector('input[name="end_time"]').addEventListener('input', calculateDuration);

    // Sugerencia de actividades basadas en la hora
    document.querySelector('input[name="start_time"]').addEventListener('change', function() {
        const time = this.value;
        const activityInput = document.querySelector('input[name="type_activity"]');
        
        if (time && !activityInput.value) {
            const hour = parseInt(time.split(':')[0]);
            
            let suggestion = '';
            if (hour >= 5 && hour <= 8) {
                suggestion = 'CORRER';
            } else if (hour >= 9 && hour <= 11) {
                suggestion = 'GIMNASIO';
            } else if (hour >= 14 && hour <= 17) {
                suggestion = 'CICLISMO';
            } else if (hour >= 18 && hour <= 20) {
                suggestion = 'CAMINATA';
            }
            
            if (suggestion) {
                activityInput.placeholder = `Sugerencia: ${suggestion}`;
            }
        }
    });
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