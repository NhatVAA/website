<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $table = 'video';
    protected $fillable = [
        'idPost',
        'idStory',
        'videoUrl',
    ];
    
    public function posts()
    {
        return $this->belongsTo(Post::class );
    }
}
