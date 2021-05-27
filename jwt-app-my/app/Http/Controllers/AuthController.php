<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Comment;
use App\Models\Categori;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function register(Request $request)
    {
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
    }

    public function login(Request $request)
    {
        $credentials = request()->only(['email', 'password']);
        $token = auth()->attempt($credentials);
        return $token;
    }

    public function logout(Request $request)
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
            return response(['message' => 'Successfully logged out']);
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response(['error' => $e->getMessage()], 401);
        }
    }
}
