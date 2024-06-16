<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;

    protected $table = 'like';
    protected $fillable = [
        'id_Post',
        'id_User',
        'content',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class  );
    }
}

