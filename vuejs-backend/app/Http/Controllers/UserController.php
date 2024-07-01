<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUser(Request $request)
    {
        $user = $request->user(); // Pobranie zautoryzowanego użytkownika
        return response()->json($user);
    }
}
