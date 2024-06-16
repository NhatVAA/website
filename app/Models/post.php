<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $table = 'post';
    protected $fillable = [
        'content',
        'privacy',
        'idUser',
    ];

    public function photos()
    {
        return $this->hasMany(Photo::class , 'idPost' );
    }
    public function videos()
    {
        return $this->hasMany(Video::class , 'idPost' );
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

}
