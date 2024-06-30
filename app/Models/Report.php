<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $table = 'reports';
    protected $fillable = [
        'id_Post',
        'id_User',
        'reason',
    ];


    public function user()
    {
        return $this->belongsTo(User::class );
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
