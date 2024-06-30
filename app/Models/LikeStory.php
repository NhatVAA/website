<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LikeStory extends Model
{
    use HasFactory;

    protected $table = 'likestory';
    protected $fillable = [
        'id_Story',
        'id_User',
    ];

    public function story()
    {
        return $this->belongsTo(Story::class );
    }
    // public function user()
    // {
    //     return $this->belongsTo(User::class, 'id_User'  );
    // }
}
