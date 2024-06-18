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
        return $this->belongsToMany(User::class, 'friendships', 'id_User', 'friend_id')->wherePivot('status', 'accepted');
    }

    public function friendRequests()
    {
        return $this->belongsToMany(User::class, 'friendships', 'friend_id', 'id_User')->wherePivot('status', 'pending');
    }

    public function sentFriendRequests()
    {
        return $this->belongsToMany(User::class, 'friendships', 'id_User', 'friend_id')->wherePivot('status', 'pending');
    }

    public function isFriendsWith($user)
    {
        return $this->friends->contains($user);
    }
    public function pendingFriends(): BelongsToMany
    {
        $pendingFriends = $this->belongsToMany(User::class, 'friends', 'user_id', 'friend_id')
            ->wherePivot('status', 'pending');
        return $pendingFriends;
    }
}
