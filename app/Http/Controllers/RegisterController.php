<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\Register as ResourcesRegister;
use App\Http\Resources\UserResource;
use App\Http\Requests\Register;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(Register $request)
    {
        $rules = $request->rules();
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Proses Register Gagal',
                'data' => $validator->errors()
            ], 400);
        }

        $data = $request->validated();
        $user = new User($data);
        $user->username = $data['username'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->save();
        return response()->json([
            'status' => true,
            'message' => 'Registrasi berhasil',
            'data' => new UserResource($user)
        ], 200);


    }
}
