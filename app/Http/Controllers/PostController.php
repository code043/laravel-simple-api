<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Auth\Access\Events\GateEvaluated;
use Illuminate\Auth\Access\Gate as AccessGate;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller implements HasMiddleware
{
    public static function middleware(){
        return [
            new Middleware('auth:sanctum', except: [
                'index', 'show'
            ])
        ];
    }
  
    public function index()
    {
        $posts = Post::all();
        return response()->json([
            'quantity' => count($posts),
            "posts" => $posts
        ], 200);
    }

   
    public function store(Request $request)
    {
        $validate = $request->validate([
            'title' => 'required|max:255',
            'body' => 'required'
        ]);
        $post = $request->user()->create($validate);
        return response()->json([
            'message' => 'Post has created',
            'post' => $post
        ], 201);
    }

    public function show(Post $post)
    {
        return response()->json([
            'post' => $post
        ], 200);
    }

  
    public function update(Request $request, Post $post)
    {
        
        Gate::authorize('modify', $post);
        $validate = $request->validate([
            'title' => 'required|max:255',
            'body' => 'required'
        ]);
        $post->update($validate);
        return response()->json([
            'message' => 'Post has updated',
            'post' => $post
        ], 200);
    }

  
    public function destroy(Post $post)
    {
        Gate::authorize('modify', $post);
        $post->delete($post);
        return response()->json([
            'message' => 'Post has deleted',
        ], 200);
    }
}
