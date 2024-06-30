<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notification';
    protected $fillable = [
        'id_User',
        'idPost',
        'content',
        // 'type',
        // 'read_at',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class, 'idPost'  );
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'id_User'  );
    }
}
