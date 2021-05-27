<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Categori;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class Controller extends BaseController
{
    public function __construct()
    {
        $this->user = JWTAuth::user(JWTAuth::getToken());
    }

    public function index()
    {
        return $this->user;
    }

    public function show()
    {
        return $this->user;
    }
    public function store(Request $request)
    {
        if ($this->user->isAdmin()) {
            $user = User::create([
                'login' => $request->input('login'),
                'email' => $request->input('email'),
                'role' => "USER",
                'full_name' => $request->input('full_name'),
                'profile_picture' => 'default',
                'rating' => "1",
                'password' => Hash::make($request->input('password'))
            ]);
            return $user;
        } else {
            return "false";
        }
    }
    public function avatar(Request $request)
    {
        if (!$data = User::where('id', $this->user->id)->first())
            return response([
                'message' => 'Invalid comment'
            ], 404);
        $data->profile_picture = $request->input('profile_picture');
        $data->save();
        return response([
            'message' => 'Correct'
        ], 200);
    }
    public function uploadAvatar(Request $request)
    {
        if ($request->file('image')) {
            $user = User::find(JWTAuth::user(JWTAuth::getToken())->id);
            $user->update([
                'image' => $image = explode('/', $request->file('image')->storeAs('avatars', $user->id . $request->file('image')->getClientOriginalName(), 's3'))[1]
            ]);

            return response([
                "message" => "Your avatar was uploaded",
                "image" => $image
            ]);
        }
    }
    public function downloadAvatar($object)
    {
        return response()->download(storage_path('app/public/avatars/' . $object), $object);
    }
    public function update(Request $request, $user_id)
    {
        if (!$data = User::where('id', $this->user->id)->first())
            return response([
                'message' => 'Invalid comment'
            ], 404);
        $data->full_name = $request->input('full_name');
        $data->rating = $request->input('rating');
        $data->email = $request->input('email');
        $data->save();
        return response([
            'message' => 'Correct'
        ], 200);
    }

    public function delete($user_id)
    {
        return User::destroy($this->user->id);
    }
}
