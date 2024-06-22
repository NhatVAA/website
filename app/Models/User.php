<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phoneNumber',
        'birth',
        'gender',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function friends()
    {
        return $this->belongsToMany(User::class, 'friendship', 'id_User', 'id_friend' )->wherePivot('status', 'accepted')->withTimestamps();
    }
    
    public function friendRequests()
    {
        return $this->belongsToMany(User::class, 'friendship', 'id_friend', 'id_User')->wherePivot('status', 'pending')->withTimestamps();
    }

    public function sentFriendRequests()
    {
        return $this->belongsToMany(User::class, 'friendship', 'id_User', 'id_friend')->wherePivot('status', 'pending')->withTimestamps();
    }

    public function isFriendsWith($user)
    {
        return $this->friends->contains($user);
    }

    
    // public function pendingFriends(): BelongsToMany
    // {
    //     $pendingFriends = $this->belongsToMany(User::class, 'friendship', 'id_User', 'id_friend')
    //         ->wherePivot('status', 'pending');
    //     return $pendingFriends;
    // }
}
