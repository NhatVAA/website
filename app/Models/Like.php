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
    ];

    public function post()
    {
        return $this->belongsTo(Post::class, 'id_Post'  );
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'id_User'  );
    }
}

