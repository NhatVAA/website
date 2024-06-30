<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class personal_access_tokens extends Model
{
    use HasFactory;

    protected $table = 'personal_access_tokens';
    protected $fillable = [
        'tokenable_type',
        'tokenable_id',
        'name',
        'token',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
