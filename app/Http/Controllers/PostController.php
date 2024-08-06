<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Post;
use Illuminate\Support\Str;
use App\Http\Requests\Posts;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Auth;
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
        $coba = Post::with(['author:id,username', 'categori:id,name', 'tags:id,name'])->get();
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
        $data['user_id'] = Auth::id();
        $data['category_id'] = $request->input('category_id');
        $data['status'] = $request->input('status', 'draft');
        
        if ($data['status'] === 'publish') {
            $data['published_at'] = $request->input('published_at', Carbon::now());
        } else {
            $data['published_at'] = null; 
        }
    
        try {
            $post = new Post($data);
            $post->save();
            
            // Tambahkan tag ke post
            if ($request->has('tags')) {
                $tags = $request->input('tags'); 
                $post->tags()->attach($tags);
            }
    
            return response()->json([
                'status' => true,
                'message' => 'Post berhasil disimpan',
                'data' => new PostResource($post->loadMissing('author:id,username', 'categori:id,name', 'tags:id,name'))
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menyimpan post',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $detail = Post::with(['author:id,username', 'categori:id,name', 'tags:id,name'])->findorFail($id);
        return new PostDetailResource($detail);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Posts $request, $id)
    {
        $post = Post::findOrFail($id);
        $data = $request->validated();

        // if ($post->status === 'publish' && isset($data['status']) && $data['status'] === 'draft') {
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'Post yang sudah dipublikasikan tidak bisa diubah menjadi draft.',
        //     ], 400);
        // }
        if ($request->has('title')) {
            $data['slug'] = Str::slug($data['title']);
        }
        if ($request->hasFile('thumbnail')) {
            if ($post->thumbnail) {
                Storage::disk('public')->delete($post->thumbnail);
            }
            $file = $request->file('thumbnail');
            $path = $file->store('thumbnails', 'public');
            $data['thumbnail'] = $path;
        }

        if (isset($data['status']) && $data['status'] === 'publish') {
            $data['published_at'] = $request->input('published_at', Carbon::now());
        } else {
            $data['published_at'] = null; 
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
        $post->delete();

        return response()->json([
            'status' => true,
            'message' => 'Post berhasil dihapus',
        ], 200);

    }
}
