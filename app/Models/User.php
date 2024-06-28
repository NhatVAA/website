<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable implements MustVerifyEmail
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
        'avatar',
        'coverimage',
        'role',
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
    protected $cascadeDeletes = ['comments', 'likes' , 'reports' , 'friend' , 'likestorys' , 'photos','videos','posts','storys', 'messages'];

    public function friends()
    {
        return $this->belongsToMany(User::class, 'friendship', 'id_User', 'id_friend' )->wherePivot('status', 'accepted')->withTimestamps();
    }
    // Người khác gửi lời mời đến mình
    public function friendRequests()
    {
        return $this->belongsToMany(User::class, 'friendship', 'id_friend', 'id_User')->wherePivot('status', 'pending')->withTimestamps();
    }
    // Mình gửi lời mời đến người khác
    public function sentFriendRequests()
    {
        return $this->belongsToMany(User::class, 'friendship', 'id_User', 'id_friend')->wherePivot('status', 'pending')->withTimestamps();
    }

    public function isFriendsWith($user)
    {
        return $this->friends->contains($user);
    }
    // public function pendingFriends():BelongsToMany
    // {
    //     $pendingFriends = $this->belongsToMany(User::class, 'friends', 'user_id', 'friend_id')
    //         ->wherePivot('status', 'pending');
    //     return $pendingFriends;
    // }
    public function friend()
    {
        return $this->hasMany(Friendship::class  )->onDelete('cascade');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class  )->onDelete('cascade');
    }
    public function likes()
    {
        return $this->hasMany(Like::class  )->onDelete('cascade');
    }
    public function likestorys()
    {
        return $this->hasMany(LikeStory::class)->onDelete('cascade');
    }
    public function photos()
    {
        return $this->hasMany(Photo::class )->onDelete('cascade');
    }
    public function videos()
    {
        return $this->hasMany(Video::class )->onDelete('cascade');
    }
    
    public function reports() {
        return $this->hasMany(Report::class)->onDelete('cascade');
    }
    public function storys()
    {
        return $this->hasMany(Story::class  )->onDelete('cascade');
    }
    public function posts()
    {
        return $this->hasMany(Post::class  )->onDelete('cascade');
    }
    public function messages()
    {
        return $this->hasMany(Message::class)->onDelete('cascade');
    }
   
}
