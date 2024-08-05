<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Post;
use Illuminate\Support\Str;
use App\Http\Requests\Posts;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\PostDetailResource;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $coba = Post::all();
        // return response()->json($coba);
        $coba = Post::with(['penulis:id,username'])->get();
        return PostResource::collection($coba);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Posts $request)
    {
        $data = $request->validated();
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $path = $file->store('thumbnails', 'public'); 
            $data['thumbnail'] = $path;
        }
        
        $data['slug'] = Str::slug($data['title']);
        $data['status'] = 1;
        $data['user_id'] = $request->input('user_id');     
        // auth()->id(); 
        $data['category_id'] = $request->input('category_id');
        $data['published_at'] = Carbon::now();

        $new = new Post($data);
        $new->save();

        return response()->json([
            'status' => true,
            'message' => 'Post berhasil disimpan',
            'data' => new PostResource($new)
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $detail = Post::with(['penulis:id,username', 'categori:id,name', 'tags:id,name'])->findorFail($id);
        return new PostDetailResource($detail);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Posts $request, string $id)
    {
        $post = Post::findOrFail($id);
        $data = $request->validated();
        if ($request->hasFile('thumbnail')) {
            if ($post->thumbnail) {
                Storage::disk('public')->delete($post->thumbnail);
            }
            $file = $request->file('thumbnail');
            $path = $file->store('thumbnails', 'public');
            $data['thumbnail'] = $path;
        }

        if ($request->has('title')) {
            $data['slug'] = Str::slug($data['title']);
        }

        $post->update($data);
        return response()->json([
            'status' => true,
            'message' => 'Post berhasil diperbarui',
            'data' => new PostResource($post)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::findOrFail($id);
        if ($post->thumbnail) {
            Storage::disk('public')->delete($post->thumbnail);
        }

        $post->delete();
        return response()->json([
            'status' => true,
            'message' => 'Post berhasil dihapus',
        ], 200);
    }
}
