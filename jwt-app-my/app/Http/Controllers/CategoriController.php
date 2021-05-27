<?php

namespace App\Http\Controllers;

use App\Models\Categori;
use Illuminate\Http\Request;
use App\Models\Post;

class CategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Categori::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $categori =  Categori::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
        ]);
        return $categori;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Categori  $categori
     * @return \Illuminate\Http\Response
     */
    public function show($categori_id)
    {
        if (!$data = Categori::where('id', $categori_id)->get()->all())
            return response([
                'message' => 'Invalid comment'
            ], 404);

        return $data;
    }

    public function showPosts($category_id)
    {
        if (!Post::categoryExists($category_id))
            return response([
                'message' => 'Invalid category'
            ], 404);
        return Post::where('category_id', $category_id)->get();
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Categori  $categori
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $category_id)
    {
        if (!$data = Categori::where('id', $category_id)->first())
            return response([
                'message' => 'Invalid comment'
            ], 404);
        $data->description = $request->input('description');
        $data->save();
        return response([
            'message' => 'Correct'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Categori  $categori
     * @return \Illuminate\Http\Response
     */
    public function destroy($category_id)
    {
        return Categori::destroy($category_id);
    }
}
