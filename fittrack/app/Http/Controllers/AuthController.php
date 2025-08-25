<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    private $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8',
        'password_confirmation' => 'required|same:password',
    ];

    private $traduccionAttributes = [
        'name' => 'nombre',
        'password' => 'contraseña',
    ];

    
    public function welcome()
    {
        $activities = Activity::where('user_id', Auth::id())->get();
        return view('welcome', compact('activities'));
    }
    
    public function index()
    {
         if (Auth::check()) {
           return redirect()->route('welcome');
        }
        
        return view('auth.login');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         $validator = Validator::make($request->all(), $this->rules);
        $validator->setAttributeNames($this->traduccionAttributes);
        if($validator->fails())
        {
            return redirect()->route('auth.register')->withInput()->withErrors($validator);
        }

        $request['password'] = bcrypt($request['password']);
        $user = User::create($request->all());

        Auth::login($user); 
        return redirect()->route('welcome');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Login de usuarios
     */
    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('welcome');            
        }

        return back()->withErrors([
            'email' => 'Credenciales incorrectas'
        ])->onlyInput('email');
    }

    /**
     * Cerrar sesión del usuario
     */
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('auth.index');
    }
}
