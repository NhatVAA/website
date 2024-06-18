<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Friendship extends Model
{
    use HasFactory;

    protected $table = 'friendship';
    protected $fillable = [
        'id_User',
        'id_friend',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_User');
    }

    public function friend()
    {
        return $this->belongsTo(User::class, 'id_friend');
    }
}
