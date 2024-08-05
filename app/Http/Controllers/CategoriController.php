<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\Categori;
use App\Http\Resources\CategoriResource;
use Illuminate\Support\Facades\Validator;

class CategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Categori $request)
    {
        $rules = $request->rules();
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Gagal Menambahkan Categori',
                'data' => $validator->errors()
            ], 400);
        }

        $data = $request->validated();
        $categori = new Categorie($data);
        $categori->name = $data['name'];
        $categori->slug = Str::slug($data['name']);
        $categori->save();
        return response()->json([
            'status' => true,
            'message' => 'Categories berhasil disimpan',
            'data' => new CategoriResource($categori)
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
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
}
