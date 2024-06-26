<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notification';
    protected $fillable = [
        'id_Post',
        'id_User',
        'type',
        'read_at',
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
