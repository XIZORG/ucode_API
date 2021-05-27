<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Models\Post;
use Tymon\JWTAuth\Facades\JWTAuth;

class CommentController extends Controller
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
    public function index($comment_id)
    {
        if (!$data = Comment::where('id', $comment_id)->get()->all())
            return response([
                'message' => 'Invalid comment'
            ], 404);

        return $data;
    }
    public function likes($comment_id)
    {
        if (!$data = Comment::where('id', $comment_id)->first())
            return response([
                'message' => 'Invalid comment'
            ], 404);

        return $data->like;
    }

    public function addLike($comment_id)
    {
        if (!$data = Comment::where('id', $comment_id)->first())
            return response([
                'message' => 'Invalid comment'
            ], 404);
        $data->like = $data->like + 1;
        $data->save();
        return response([
            'message' => 'Correct'
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $post_id)
    {
        if(!$post = Post::postExists($post_id)){
            return response([
                'message' => 'Post not find'
            ], 404);
        }
        $comment = Comment::create([
            'author' => $this->user->login,
            'like' => 0,
            'content' => $request->input('content'),
            'post_id' => $post_id,
        ]);
        return $comment;
    }

    public function removeLike($comment_id)
    {
        if (!$data = Comment::where('id', $comment_id)->first())
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$comment_id)
    {
        if (!$data = Comment::where('id', $comment_id)->first())
            return response([
                'message' => 'Invalid comment'
            ], 404);
        $data->content = $request->input('content');
        $data->save();
        return response([
            'message' => 'Correct'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy($comment_id)
    {
        return Comment::destroy($comment_id);
    }
}
