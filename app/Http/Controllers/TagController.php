<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\Tags;
use App\Http\Resources\TagResource;
use Illuminate\Support\Facades\Validator;

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
        $rules = $request->rules();
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Gagal Menambahkan Tag baru',
                'data' => $validator->errors()
            ], 400);
        }

        $data = $request->validated();
        $tag = new Tag($data);
        $tag->name = $data['name'];
        $tag->slug = Str::slug($data['name']);
        $tag->save();
        return response()->json([
            'status' => true,
            'message' => 'Tag berhasil disimpan',
            'data' => new TagResource($tag)
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
