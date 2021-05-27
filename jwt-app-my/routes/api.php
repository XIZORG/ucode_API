<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/user-create', function(Request $request) {
    App\Models\User::create([
        'login' => 'SomeName2',
        'email' => 'some2@gmail.com',
        'role' => "USER",
        'full_name' => 'SomeName2',
        'profile_picture' => 'some',
        'rating' => "2",
        'password' => Hash::make('123456')
    ]);
});

Route::get('/post-create', function(Request $request) {
    App\Models\Post::create([
        'author'=> 'first',
        'title'=> 'first',
        'publish_date'=> 'ssss',
        'status'=> 'egeg',
        'content'=> 'dfd',
        'category_id'=> '3',
    ]);
});

Route::post('/login', function() {
    $credentials = request()->only(['email', 'password']);

    $token = auth()->attempt($credentials);
    return $token;
});

// Route::prefix('admin')->middleware('auth')->group(function () {
//     Route::get('/get', 'App\Http\Controllers\AdminController@index');
// });


//auth module
Route::prefix('auth')->group(function () {
    Route::post('/register', 'App\Http\Controllers\AuthController@register');
    Route::post('/login', 'App\Http\Controllers\AuthController@login');
    Route::post('/logout', 'App\Http\Controllers\AuthController@logout');
});

//user module
Route::get('/users/avatar/{path}', 'App\Http\Controllers\Controller@downloadAvatar');
Route::post('/users/avatar/upload', 'App\Http\Controllers\Controller@uploadAvatar');
Route::prefix('users')->middleware('auth')->group(function () {
    Route::get('', 'App\Http\Controllers\Controller@index');
    Route::get('/{user_id}', 'App\Http\Controllers\Controller@show');
    Route::post('', 'App\Http\Controllers\Controller@store');
    Route::post('/avatar', 'App\Http\Controllers\Controller@avatar');
    Route::post('/{user_id}', 'App\Http\Controllers\Controller@update');
    Route::delete('/{user_id}', 'App\Http\Controllers\Controller@delete');
});

//post module
Route::prefix('posts')->group(function () {
    Route::get('/{post_id}/categories', 'App\Http\Controllers\PostController@showCategories');
    Route::get('/{post_id}/comments', 'App\Http\Controllers\PostController@showComments');
    Route::get('/{post_id}/like', 'App\Http\Controllers\PostController@showPostLikes');
});


Route::get('/posts/getAll', 'App\Http\Controllers\PostController@index');
Route::prefix('posts')->middleware('auth')->group(function () {
    Route::get('/{post_id}', 'App\Http\Controllers\PostController@getPost');
    Route::post('/{post_id}', 'App\Http\Controllers\PostController@update');
    Route::post('', 'App\Http\Controllers\PostController@store');
    Route::post('/{post_id}/comments', 'App\Http\Controllers\CommentController@store');
    Route::post('/{post_id}/like', 'App\Http\Controllers\PostController@addLike');
    Route::delete('/{post_id}', 'App\Http\Controllers\PostController@destroy');
    Route::delete('/{post_id}/like', 'App\Http\Controllers\PostController@deleteLike');
});

//Route::apiResource('posts', 'App\Http\Controllers\PostController');

//categories module
Route::prefix('categories')->middleware('auth')->group(function () {
    Route::get('', 'App\Http\Controllers\CategoriController@index');
    Route::get('/{category_id}', 'App\Http\Controllers\CategoriController@show');
    Route::get('/{category_id}/posts', 'App\Http\Controllers\CategoriController@showPosts');
    Route::post('', 'App\Http\Controllers\CategoriController@store');
    Route::post('/{category_id}', 'App\Http\Controllers\CategoriController@update');
    Route::delete('/{category_id}', 'App\Http\Controllers\CategoriController@destroy');
});


//comments module
Route::prefix('comments')->middleware('auth')->group(function () {
    Route::get('/{comment_id}', 'App\Http\Controllers\CommentController@index');
    Route::post('/{comment_id}', 'App\Http\Controllers\CommentController@update');
    Route::delete('/{comment_id}', 'App\Http\Controllers\CommentController@destroy');

    Route::get('/{comment_id}/like', 'App\Http\Controllers\CommentController@likes');
    Route::post('/{comment_id}/like', 'App\Http\Controllers\CommentController@addLike');
    Route::delete('/{comment_id}/like', 'App\Http\Controllers\CommentController@removeLike');
});

