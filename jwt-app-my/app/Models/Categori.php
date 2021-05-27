<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Post;

class Categori extends Model
{
    use HasFactory, CrudTrait;

    protected $fillable = [
        'title',
        'description',
    ];

    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }
}
