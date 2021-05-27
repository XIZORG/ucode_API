<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Categori;

class Post extends Model
{
    use HasFactory, CrudTrait;

    protected $fillable = [
        'author',
        'title',
        'status',
        'content',
        'category_id',
        'like',
    ];

    protected $casts = [
        'author' => 'string',
        'title' => 'string',
    ];

    static public function postExists($post_id)
    {
        if (Post::find($post_id) === null)
            return false;
        return true;
    }

    static public function categoryExists($category_id)
    {
        if (Categori::find($category_id) === null)
            return false;
        return true;
    }

}
