<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    use HasFactory;

    protected $table = 'story';
    protected $fillable = [
        'privacy',
        'id_User',
    ];

    public function photos()
    {
        return $this->hasMany(Photo::class , 'id_Story' );
    }
    public function videos()
    {
        return $this->hasMany(Video::class , 'id_Story' );
    }
    public function likestorys()
    {
        return $this->hasMany(LikeStory::class, "id_Story");
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'id_User');
    }

}
