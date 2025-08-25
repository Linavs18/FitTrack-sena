<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Support\Facades\Auth;

class WelcomeController extends Controller {
    public function welcome()
    {
        // Obtener actividades del usuario autenticado
        $activities = Activity::where('user_id', Auth::id())->get();

        return view('welcome', compact('activities'));
    }
}