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
        'time',
        'distance',
        'calories',
    ];

    /**
     * Relación con User (muchas actividades pertenecen a un usuario).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
