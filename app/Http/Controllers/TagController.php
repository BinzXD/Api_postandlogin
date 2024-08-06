<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Http\Requests\Tags;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Resources\TagResource;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class TagController extends Controller
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
    public function store(Tags $request)
    {
        try {
            $rules = $request->rules();
            $validator = Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $data = $validator->validated();
            $tag = new Tag();
            $tag->name = $data['name'];
            $tag->slug = Str::slug($data['name']);
            $tag->save();
    
            return response()->json([
                'status' => true,
                'message' => 'Tag berhasil disimpan',
                'data' => new TagResource($tag)
            ], 200);
    
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Gagal Menambahkan Tag baru',
                'data' => $e->errors()
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan tag',
                'error' => $e->getMessage()
            ], 500);
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
    public function update(Tags $request, $id)
    {
        $edit = Tag::findOrFail($id);
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
