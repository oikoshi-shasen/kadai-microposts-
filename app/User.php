<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    public function microposts()
    {
        return $this->hasMany(Micropost::class);
    }
    
    public function loadRelationshipCounts()
    {
        $this->loadCount('microposts');
        $this->loadCount('followings');
        $this->loadCount('followers');
        $this->loadCount('favorite_ings');
    }
    
    public function followings()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'user_id', 'follow_id')->withTimestamps();
    }
    
    
       public function followers()
    {
        return $this->belongsToMany(User::class, 'user_follow', 'follow_id', 'user_id')->withTimestamps();
    }
    
    
      public function follow($userId)
    {
        // すでにフォローしているか
        $exist = $this->is_following($userId);
        // 対象が自分自身かどうか
        $its_me = $this->id == $userId;
        if ($exist || $its_me) {
            // フォロー済み、または、自分自身の場合は何もしない
            return false;
        } else {
            // 上記以外はフォローする
            $this->followings()->attach($userId);
            return true;
        }
    }
    
    public function unfollow($userId)
    {
        // すでにフォローしているか
        $exist = $this->is_following($userId);
        // 対象が自分自身かどうか
        $its_me = $this->id == $userId;

        if ($exist && !$its_me) {
            // フォロー済み、かつ、自分自身でない場合はフォローを外す
            $this->followings()->detach($userId);
            return true;
        } else {
            // 上記以外の場合は何もしない
            return false;
        }
    }
    
    
      public function is_following($userId)
    {
        // フォロー中ユーザの中に $userIdのものが存在するか
        return $this->followings()->where('follow_id', $userId)->exists();
    }

    
    public function feed_microposts()
    {
        // このユーザがフォロー中のユーザのidを取得して配列にする
        $userIds = $this->followings()->pluck('users.id')->toArray();
        // このユーザのidもその配列に追加
        $userIds[] = $this->id;
        // それらのユーザが所有する投稿に絞り込む
        return Micropost::whereIn('user_id', $userIds);
    }


    public function favorite_ings()
    {
         return $this->belongsToMany(Micropost::class, 'favorites', 'user_id', 'favorite_id')->withTimestamps();
    }

    
     public function is_favorite_ing($microposts_Id)
    {
        
        return $this->favorite_ings()->where('favorite_id', $microposts_Id)->exists();
    }


    public function favorite($microposts_Id)
    {
        $exist = $this->is_favorite_ing($microposts_Id);
        if ($exist){
            return false;
        } else {
            
            $this->favorite_ings()->attach($microposts_Id);
            return true;
        }
    }
    
    public function un_favorite($microposts_Id)
    {
        $exist = $this->is_favorite_ing($microposts_Id);
        if ($exist){
            $this->favorite_ings()->detach($microposts_Id);
            return true;
        } else {
            return false;
        }
    }
    
       public function feed_favorites()
     {
         
         $favorite_Ids = $this->favorite_ings()->pluck('microposts.id')->toArray();
        
         // それらのユーザが所有する投稿に絞り込む
         return Micropost::wherein('id', $favorite_Ids);
     }

    


}
