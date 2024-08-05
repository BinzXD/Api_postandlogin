<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LogOutControlller extends Controller
{
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'status'=> true,
            'message' => 'Berhasil LogOut ',
        ],200);

    }
}
