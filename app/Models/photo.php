<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $table = 'photo';
    protected $fillable = [
        'id_Post',
        'id_Story',
        'photoUrl',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class  );
    }
}

