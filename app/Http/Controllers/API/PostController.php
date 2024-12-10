<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $posts = Post::paginate(5);
        return response()->json([
            'status' => true,
            'message' => 'Post fetched',
            'data' => $posts
        ]);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "title" => "required",
            "body" => "required",
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "message" => "Validation Error",
                "errors" => $validator->errors()->all()
            ]);
        }
        $post = Post::create([
            'title' => $request->title,
            'body' => $request->body
        ]);
        return response()->json(
            [
                'status' => 'true',
                'message' => "Post Created",
                'data' => $post
            ]
        );
    }
    public function show(Request $request, $id)
    {
        $post = Post::find($id);
        return response()->json([
            'status' => true,
            'message' => 'Posts Shown',
            'data' => $post
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            "title" => "required",
            "body" => "required",
        ]);
        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "message" => "Validation Error",
                "errors" => $validator->errors()->all()
            ]);
        }
        $post = Post::find($id);
        $post->title = $request->title;
        $post->body = $request->body;
        $post->save();

        return response()->json([
            'status' => true,
            'message' => 'Post Updated',
            'data' => $post
        ]);
    }
    public function destroy(Request $request, $id)
    {
        $post = Post::destroy($id);

        return response()->json([
            'status' => true,
            'message' => "Post Deleted",
            'data' => $post
        ]);
    }
}
