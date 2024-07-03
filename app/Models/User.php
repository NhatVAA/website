<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Sanctum\HasApiTokens;

// use App\Notifications\Notifications\Notificationss ;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable ;


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
        'email_verified_at',
        'remember_token',
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
    // protected static function boot()
    // {
    //     parent::boot();

    //     // Xử lý sự kiện xóa User
    //     static::deleting(function ($user) {
    //         // Xóa các bài post của user
    //         $user->posts()->delete();
    //     });
    // }
    // protected $cascadeDeletes = ['comments', 'likes' , 'reports' , 'friend' , 'likestorys' , 'photos','videos','posts','storys', 'messages','notifications','personal_access_tokens'];

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
  

    public function friend()
    {
        return $this->belongsToMany(User::class, 'friendship', 'id_User', 'id_friend'  );
    }
    public function messages()
    {
        return $this->belongsToMany(User::class, 'message', 'sender_id', 'receiver_id');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class, 'id_User' );
    }
    public function likes()
    {
        return $this->hasMany(Like::class, 'id_User'  );
    }
    public function likestorys()
    {
        return $this->hasMany(LikeStory::class, 'id_User');
    }
    
    public function reports() {
        return $this->hasMany(Report::class, 'id_User');
    }
    public function storys()
    {
        return $this->hasMany(Story::class , 'id_User' );
    }
    public function posts()
    {
        return $this->hasMany(Post::class, 'id_User'  );
    }
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'id_User');
    }
    public function personal_access_tokens()
    {
        return $this->belongsTo(personal_access_tokens::class);
    }
    
    // public function sendEmailVerificationNotification()
    // {
    //     $this->notify(new Notificationss);
    // }
    // public function PasswordResets()
    // {
    //     return $this->belongsTo(PasswordResets::class);
    // }
}
