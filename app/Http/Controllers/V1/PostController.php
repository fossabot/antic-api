<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        return Post::all();
    }

    public function store(Request $request)
    {
        \Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required',
        ])->validate();

        $content = $request->input('content');
        $title = $request->input('title');
        $resp = Post::create([
            'title' => $title,
            'content' => $content,
        ]);

        return $resp;
    }

    public function show(Post $post)
    {
        return $post;
    }

    public function update(Request $request, Post $post)
    {
        $post->update($request->all());

        return $post;
    }
}
