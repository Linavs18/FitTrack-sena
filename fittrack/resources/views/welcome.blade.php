@extends('templates.base')
@section('title', 'Actividades')
@section('header', 'Actividades')

{{-- Incluir estilos personalizados --}}

@section('content')
<label class="activities-title" style="color: white">Dashboard de Actividades</label>

{{-- Sección de Gráficas --}}
@if($activities->count() > 0)
<div class="dashboard-section fade-in">
    <h2 class="dashboard-title">
        <i class="fas fa-chart-line chart-icon"></i>
        Estadísticas de Entrenamiento
    </h2>
    
    <div class="charts-grid">
        {{-- Gráfica de Horas por Semana --}}
        <div class="chart-container">
            <h3 class="chart-title">
                <i class="fas fa-clock chart-icon"></i>
                Horas de Entrenamiento por Semana
            </h3>
            <div class="chart-canvas-container">
                <canvas id="hoursChart"></canvas>
            </div>
            <div class="mt-3 text-center">
                <span class="metric-indicator">
                    <span class="metric-label">Total:</span>
                    <span class="metric-number" id="totalHours">0h</span>
                </span>
                <span class="metric-indicator">
                    <span class="metric-label">Promedio:</span>
                    <span class="metric-number" id="avgHours">0h</span>
                </span>
            </div>
        </div>

        {{-- Gráfica de Calorías por Semana --}}
        <div class="chart-container">
            <h3 class="chart-title">
                <i class="fas fa-fire chart-icon"></i>
                Calorías Quemadas por Semana
            </h3>
            <div class="chart-canvas-container">
                <canvas id="caloriesChart"></canvas>
            </div>
            <div class="mt-3 text-center">
                <span class="metric-indicator">
                    <span class="metric-label">Total:</span>
                    <span class="metric-number" id="totalCalories">0 cal</span>
                </span>
                <span class="metric-indicator">
                    <span class="metric-label">Promedio:</span>
                    <span class="metric-number" id="avgCalories">0 cal</span>
                </span>
            </div>
        </div>

        {{-- Gráfica de Kilómetros por Semana --}}
        <div class="chart-container">
            <h3 class="chart-title">
                <i class="fas fa-route chart-icon"></i>
                Kilómetros Recorridos por Semana
            </h3>
            <div class="chart-canvas-container">
                <canvas id="distanceChart"></canvas>
            </div>
            <div class="mt-3 text-center">
                <span class="metric-indicator">
                    <span class="metric-label">Total:</span>
                    <span class="metric-number" id="totalDistance">0 km</span>
                </span>
                <span class="metric-indicator">
                    <span class="metric-label">Promedio:</span>
                    <span class="metric-number" id="avgDistance">0 km</span>
                </span>
            </div>
        </div>
    </div>
</div>
@else
<div class="no-data-message fade-in">
    <i class="fas fa-chart-line no-data-icon"></i>
    <h3 class="no-data-title">No hay datos para mostrar gráficas</h3>
    <p class="no-data-text">Crea algunas actividades para ver tus estadísticas de entrenamiento</p>
    <a href="{{ route('activity.create') }}" class="btn btn-create-activity">
        <i class="fas fa-plus"></i> Crear Primera Actividad
    </a>
</div>
@endif

{{-- Resumen estadístico --}}
@if($activities->count() > 0)
<div class="row">
    <div class="col-12">
        <div class="card stats-card fade-in">
            <div class="card-header stats-card-header">
                <h5 class="stats-card-title">
                    <i class="fas fa-chart-bar"></i> Resumen de Actividades
                </h5>
            </div>
            <div class="card-body stats-card-body">
                <div class="row">
                    <div class="col-md-3 stats-item">
                        <h3 class="stats-number-green">{{ $activities->count() }}</h3>
                        <small class="stats-label">Total Actividades</small>
                    </div>
                    <div class="col-md-3 stats-item">
                        @php
                            $totalDuration = $activities->sum('duration');
                            $totalHours = floor($totalDuration / 3600);
                            $totalMinutes = floor(($totalDuration % 3600) / 60);
                        @endphp
                        <h3 class="stats-number-blue">{{ $totalHours }}h {{ $totalMinutes }}m</h3>
                        <small class="stats-label">Tiempo Total</small>
                    </div>
                    <div class="col-md-3 stats-item">
                        <h3 class="stats-number-green">{{ number_format($activities->whereNotNull('distance')->sum('distance'), 2) }}</h3>
                        <small class="stats-label">Kilómetros</small>
                    </div>
                    <div class="col-md-3 stats-item">
                        <h3 class="stats-number-blue">{{ number_format($activities->whereNotNull('calories')->sum('calories')) }}</h3>
                        <small class="stats-label">Calorías</small>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

    // Datos de actividades desde PHP
    const activities = @json($activities);
    
    // Procesar datos por semanas
    function processWeeklyData() {
        const weeklyData = {};
        
        activities.forEach(activity => {
            const date = new Date(activity.date);
            const week = getWeekNumber(date);
            const weekKey = `Semana ${week}`;
            
            if (!weeklyData[weekKey]) {
                weeklyData[weekKey] = {
                    hours: 0,
                    calories: 0,
                    distance: 0
                };
            }
            
            // Convertir duración de segundos a horas
            weeklyData[weekKey].hours += activity.duration / 3600;
            weeklyData[weekKey].calories += activity.calories || 0;
            weeklyData[weekKey].distance += activity.distance || 0;
        });
        
        return weeklyData;
    }
    
    // Obtener número de semana
    function getWeekNumber(date) {
        const d = new Date(Date.UTC(date.getFullYear(), date.getMonth(), date.getDate()));
        const dayNum = d.getUTCDay() || 7;
        d.setUTCDate(d.getUTCDate() + 4 - dayNum);
        const yearStart = new Date(Date.UTC(d.getUTCFullYear(), 0, 1));
        return Math.ceil((((d - yearStart) / 86400000) + 1) / 7);
    }
    
    // Configuración de colores
    const colors = {
        primary: '#4CAF50',
        secondary: '#2196F3',
        gradient: ['#4CAF50', '#2196F3', '#FF9800', '#9C27B0', '#F44336']
    };
    
    // Crear gráficas
    function createCharts() {
        if (activities.length === 0) return;
        
        const weeklyData = processWeeklyData();
        const weeks = Object.keys(weeklyData).sort();
        
        // Datos para las gráficas
        const hoursData = weeks.map(week => weeklyData[week].hours.toFixed(1));
        const caloriesData = weeks.map(week => weeklyData[week].calories.toFixed(0));
        const distanceData = weeks.map(week => weeklyData[week].distance.toFixed(2));
        
        // Actualizar métricas
        updateMetrics(hoursData, caloriesData, distanceData);
        
        // Configuración común de gráficas
        const commonOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    }
                },
                x: {
                    grid: {
                        color: 'rgba(0,0,0,0.1)'
                    }
                }
            }
        };
        
        // Gráfica de Horas
        new Chart(document.getElementById('hoursChart'), {
            type: 'line',
            data: {
                labels: weeks,
                datasets: [{
                    label: 'Horas',
                    data: hoursData,
                    backgroundColor: 'rgba(76, 175, 80, 0.2)',
                    borderColor: colors.primary,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: colors.primary,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 6
                }]
            },
            options: {
                ...commonOptions,
                plugins: {
                    ...commonOptions.plugins,
                    title: {
                        display: false
                    }
                }
            }
        });
        
        // Gráfica de Calorías
        new Chart(document.getElementById('caloriesChart'), {
            type: 'bar',
            data: {
                labels: weeks,
                datasets: [{
                    label: 'Calorías',
                    data: caloriesData,
                    backgroundColor: colors.gradient.map(color => color + '80'),
                    borderColor: colors.gradient,
                    borderWidth: 2,
                    borderRadius: 8,
                    borderSkipped: false
                }]
            },
            options: commonOptions
        });
        
        // Gráfica de Distancia
        new Chart(document.getElementById('distanceChart'), {
            type: 'line',
            data: {
                labels: weeks,
                datasets: [{
                    label: 'Kilómetros',
                    data: distanceData,
                    backgroundColor: 'rgba(33, 150, 243, 0.2)',
                    borderColor: colors.secondary,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: colors.secondary,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 6
                }]
            },
            options: commonOptions
        });
    }
    
    // Actualizar métricas
    function updateMetrics(hoursData, caloriesData, distanceData) {
        const totalHours = hoursData.reduce((a, b) => parseFloat(a) + parseFloat(b), 0);
        const totalCalories = caloriesData.reduce((a, b) => parseFloat(a) + parseFloat(b), 0);
        const totalDistance = distanceData.reduce((a, b) => parseFloat(a) + parseFloat(b), 0);
        
        const avgHours = (totalHours / hoursData.length) || 0;
        const avgCalories = (totalCalories / caloriesData.length) || 0;
        const avgDistance = (totalDistance / distanceData.length) || 0;
        
        document.getElementById('totalHours').textContent = `${totalHours.toFixed(1)}h`;
        document.getElementById('avgHours').textContent = `${avgHours.toFixed(1)}h`;
        document.getElementById('totalCalories').textContent = `${totalCalories.toFixed(0)} cal`;
        document.getElementById('avgCalories').textContent = `${avgCalories.toFixed(0)} cal`;
        document.getElementById('totalDistance').textContent = `${totalDistance.toFixed(2)} km`;
        document.getElementById('avgDistance').textContent = `${avgDistance.toFixed(2)} km`;
    }
    
    // Inicializar gráficas cuando se carga la página
    document.addEventListener('DOMContentLoaded', function() {
        createCharts();
    });

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