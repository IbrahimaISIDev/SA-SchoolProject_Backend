<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Services\Utilisateurs\ServiceUtilisateur;
use Illuminate\Http\Request;

class ConnexionController extends Controller
{
    protected $serviceUtilisateur;

    public function __construct(ServiceUtilisateur $serviceUtilisateur)
    {
        $this->serviceUtilisateur = $serviceUtilisateur;
    }

    public function connexion(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (auth()->attempt($credentials)) {
            $user = auth()->user();
            $token = $user->createToken('auth_token')->plainTextToken;
            
            return response()->json([
                'user' => $user,
                'token' => $token
            ]);
        }

        return response()->json([
            'message' => 'Identifiants incorrects'
        ], 401);
    }

    public function deconnexion()
    {
        auth()->user()->tokens()->delete();
        return response()->json(['message' => 'Déconnecté avec succès']);
    }
}