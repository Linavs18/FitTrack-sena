<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $table = 'activity';

    // Campos asignables en masa (mass assignment)
    protected $fillable = [
        'user_id',
        'type_activity', 
        'date',
        'duration',      // ← ASEGÚRATE DE QUE ESTÉ AQUÍ
        'distance',
        'calories'
    ];

    protected $casts = [
        'date' => 'date',
        'distance' => 'float',
        'calories' => 'integer',
        'duration' => 'integer'  // ← Y AQUÍ TAMBIÉN
    ];

    // Relación con User (cuando la implementes)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessor para formatear la duración
    public function getFormattedDurationAttribute()
    {
        $hours = floor($this->duration / 3600);
        $minutes = floor(($this->duration % 3600) / 60);
        
        return sprintf('%02d:%02d', $hours, $minutes);
    }
}
