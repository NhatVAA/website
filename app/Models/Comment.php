<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'comment';
    protected $fillable = [
        'content',
        'id_Post',
        'id_User',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class );
    }
}

