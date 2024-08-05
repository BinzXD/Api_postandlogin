<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use App\Http\Requests\Login;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login(Login $request)
    {
        Log::info('Login request received', $request->all());
        $rules = $request->rules();
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Proses login Gagal',
                'data' => $validator->errors()
            ], 400);
        }

        $data = $request->validated();
        $user = User::where('username', $data['username'])->first();

        // Jika gagal
        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Username dan password tidak sesuai'
            ], 401);
        }
        
        // Jika berhasil
        return response()->json([
            'status'=> true,
            'message' => 'Berhasil Login ',
            // 'token' => $user->token = Str::uuid()->toString(),
            'token' => $user->createToken('user login')->plainTextToken,
        ],200);
        
       


    }
    
    public function index(Request $request)
    {
        return response()->json(Auth::user());
    }
}
