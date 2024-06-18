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
        'id_User',
    ];

    public function photos()
    {
        return $this->hasMany(Photo::class , 'id_Post' );
    }
    public function videos()
    {
        return $this->hasMany(Video::class , 'id_Post' );
    }
    public function comments()
    {
        return $this->hasMany(Comment::class ,"id_Post");
    }
    public function likes()
    {
        return $this->hasMany(Like::class, "id_Post");
    }
    public function user()
    {
        return $this->belongsTo(User::class  );
    }

}
