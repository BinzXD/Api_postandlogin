<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\Categori;
use App\Http\Resources\CategoriResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

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
        try {
            $rules = $request->rules();
            $validator = Validator::make($request->all(), $rules);
            
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            $data = $validator->validated();
            $categori = new Categorie($data);
            $categori->name = $data['name'];
            $categori->slug = Str::slug($data['name']);
            $categori->save();
    
            return response()->json([
                'status' => true,
                'message' => 'Categories berhasil disimpan',
                'data' => new CategoriResource($categori)
            ], 200);
    
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Gagal Menambahkan Categori',
                'data' => $e->errors()
            ], 400);
        }
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
    public function update(Categori $request, $id)
    {
        $edit = Categorie::findOrFail($id);
        $data = $request->validated();
        if ($request->has('name')) {
            $data['slug'] = Str::slug($data['name']);
        }
        $edit->update($data);
        return response()->json([
            'status' => true,
            'message' => 'Berhasil diubah',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
