<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\Categori;
use Illuminate\Http\Request;
use App\Http\Requests\PostRequest;
use Tymon\JWTAuth\Facades\JWTAuth;

class PostController extends Controller
{

    public function __construct()
    {
        $this->user = JWTAuth::user(JWTAuth::getToken());
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Post::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $post = Post::create([
            'status' => 'ACTIVE',
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'category_id' => $request->input('category_id'),
            'author' => $this->user->login,
            'like' => 0,
        ]);
        return $post;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function getPost($post_id)
    {
        if (!$data = Post::where('id', $post_id)->get()->all())
            return response([
                'message' => 'Invalid post'
            ], 404);

        return $data;
    }

    public function showComments($post_id)
    {
        if (!Post::postExists($post_id))
            return response()->json([
                'message' => 'This post does not exist!'
            ], 404);

        if (!$data = Comment::where('post_id', $post_id)->get()->toArray())
            return response([
                'message' => 'No comments'
            ], 404);

        return $data;
    }

    public function showCategories($post_id)
    {
        try {
            if (!$data =  Post::find($post_id)->category_id)
                return response([
                    'message' => 'Invalid post!'
                ], 404);

            $result = [];
            foreach ($data as $value) {
                if (!$title = Categori::find($value)->title)
                    continue;
                else
                    array_push($result, $title);
            }

            return response($result);
        } catch (\Exception $e) {
            return response([
                'message1' => $e->getMessage()
            ], 401);
        }
    }

    public function addLike($post_id)
    {
        if (!$data = Post::where('id', $post_id)->first())
            return response([
                'message' => 'Invalid comment'
            ], 404);
        $data->like = $data->like + 1;
        $data->save();
        return response([
            'message' => 'Correct'
        ], 200);
    }

    public function showPostLikes($post_id)
    {
        if (!$data = Post::where('id', $post_id)->get()->all())
            return response([
                'message' => 'Invalid post'
            ], 404);

        return $data;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $post_id)
    {
        if (!$data = Post::where('id', $post_id)->first())
            return response([
                'message' => 'Invalid comment'
            ], 404);
        $data->content = $request->input('content');
        $data->title = $request->input('title');
        $data->category_id = (int)$request->input('category_id');
        $data->save();
        return response([
            'message' => 'Correct'
        ], 200);
    }

    public function removeLike($post_id)
    {
        if (!$data = Post::where('id', $post_id)->first())
            return response([
                'message' => 'Invalid comment'
            ], 404);
        $data->like = $data->like - 1;
        $data->save();
        return response([
            'message' => 'Correct'
        ], 200);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy($post_id)
    {
        return Post::destroy($post_id);
    }
}
