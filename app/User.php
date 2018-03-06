<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Auth\Passwords\CanResetPassword;
use App\Helpers\GroupHelper;
use DB;

class User extends Authenticatable
{
    /**
     *
     * The table where this model belongs to
     * 
     * @var string 
     */
    protected $table = 'hus_user';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'firstname', 'lastname', 'email', 'password', 'confirmation_code', 'hus_group_5_id'
        ,'hus_group_4_id', 'hus_group_3_id', 'hus_group_2_id', 'hus_group_1_id'
    ];
    
    /**
     *  Attributes for mass update
     * @var array 
     */
    public $fields = [
        'username', 'firstname', 'lastname'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'isAdmin', 'points', 'confirmed', 'confirmation_code'
    ];
    
    protected $casts = [
        'isAdmin' => 'boolean',
        'id' => 'integer',
        'hus_group_1_id' => 'integer',
        'hus_group_2_id' => 'integer',
        'hus_group_3_id' => 'integer',
        'hus_group_4_id' => 'integer',
        'hus_group_5_id' => 'integer',
        'points' => 'integer'
    ];
    
    /**
     * 
     * Returns all the events that the user has in the database.
     * 
     * @return Event
     * @see Event
     */
    public function events()
    {
        return $this->hasMany('App\Event', 'user_fk');
    }
    
    /**
     * 
     * Returns the events that are yet to be completed or are incompleted in the past.
     * 
     * @return Event
     * @see Event
     */ 
    public function incompleteEvents()
    {
        return $this->events()
                ->where('completed', false)
                ->orderBy('start_date', 'desc')
                ->get();
    }
    /**
     * 
     * Returns all the completed events descending from newest to oldest.
     * 
     * @return Event
     * @see Event
     */
    public function completed()
    {
        return $this->hasMany('App\Event', 'user_fk')->where('completed', '=', true);
    }
    /**
     * 
     * Returns the user settings.
     * 
     * @return Settings
     */
    public function settings()
    {
        return $this->hasOne('App\Settings', 'user_fk');
    }
    /**
     * 
     * Returns the security logs for user.
     * 
     * @return Settings
     */
    public function logs()
    {
        return $this->hasMany('App\SecurityLog', 'user_id')->orderBy('created_at', 'asc');
    }
    /*
     * Returns the user with a specific confirmation_code
     * 
     * @return User
     */
    public static function withConfirmationCode($confirmation_code)
    {
        return User::where('confirmation_code', '=', $confirmation_code)->first();
    }
    
    public function addConfirmationCode($confirmation_code)
    {
        $this->confirmation_code = $confirmation_code;
        $this->save();
        
        return $this->confirmation_code;
    }
    /*
     * Returns the level of the user's group
     */
    public function level()
    {
        if($this->hus_group_5_id !== null)
        {
            return 5;
        }
        else if($this->hus_group_4_id !== null)
        {
            return 4;
        }
        else if($this->hus_group_3_id !== null)
        {
            return 3;
        }
        else if($this->hus_group_2_id !== null)
        {
            return 2;
        }
        else
        {
            return 1;
        }
    }
   
    /*
     * Returns the user's group
     */
    public function group()
    { 
        return GroupHelper::forUser($this, $this->level());
    }
    
    /*
     * Returns user's friends
     */
    public function myFriends()
    {
        return $this->belongsToMany('App\User', 'hus_friends', 'user', 'friend')
                ->wherePivot('accepted', '=', 1)
                ->withPivot('accepted');
    }
    /*
     * Returns users that are friends with user
     */
    public function friendOf()
    {
        return $this->belongsToMany('App\User', 'hus_friends', 'friend', 'user')
                ->wherePivot('accepted', '=', 1)
                ->withPivot('accepted');
    }
    
    public function getFriendsAttribute()
    {
        if ( ! array_key_exists('friends', $this->relations))
        {
            $this->loadFriends();
        }

        return $this->getRelation('friends');
    }

    protected function loadFriends()
    {
        if ( ! array_key_exists('friends', $this->relations))
        {
            $friends = $this->mergeFriends();

            $this->setRelation('friends', $friends);
        }
    }
    
    protected function mergeFriends()
    {
        return $this->myFriends->merge($this->friendOf);
    }
    
    public function mergeAllFriends()
    {
        return $this->myAllFriends->merge($this->friendOfAll);
    }
    public function myAllFriends()
    {
        return $this->belongsToMany('App\User', 'hus_friends', 'user', 'friend')
                ->withPivot('accepted');
    }
    public function friendOfAll()
    {
        return $this->belongsToMany('App\User', 'hus_friends', 'friend', 'user')
                ->withPivot('accepted');
    }
    
    public function getFriendsAllAttribute()
    {
        if ( ! array_key_exists('friendsAll', $this->relations))
        {
            $this->loadAllFriends();
        }

        return $this->getRelation('friendsAll');
    }

    protected function loadAllFriends()
    {
        if ( ! array_key_exists('friendsAll', $this->relations))
        {
            $friendsAll = $this->mergeAllFriends();

            $this->setRelation('friendsAll', $friendsAll);
        }
    }
    
    public function friendRequests($getBoolOnly = false)
    {
        $pending = $this->belongsToMany('App\User', 'hus_friends', 'friend', 'user')
                ->wherePivot('accepted', '=', 0)
                ->withPivot('accepted');
        return $getBoolOnly ? $pending->count() > 0 : $pending;
    }
    
    public function sendFriendRequest($friend_id)
    {
        try
        {
            DB::table('hus_friends')->insert([
                "user" => $this->id, 
                "friend" => $friend_id, 
                "created_at" =>  \Carbon\Carbon::now(),
                "updated_at" => \Carbon\Carbon::now(),]);
        }
        catch(Exception $e)
        {
            return false;
        }
        
        return true;
    }
    
    public function messages($lastID = 0)
    {
        if($lastID > 1)
        {
            return $this->hasMany('App\Message', 'user_id')
                    ->where('id', '>', $lastID)->get();
        }
        return $this->hasMany('App\Message', 'user_id');
    }
    
    public function isFriendOf($user_id)
    {
        $friends = $this->friends;
        $person = User::find($user_id);
        return isset($person) && $friends->contains($person);
    }
    
    public function buddy()
    {
        return $this->hasOne('App\Buddy', 'owner_id');
    }
    
    public function achievements()
    {
        return $this->belongsToMany('App\Achievement', 'hus_achievement_user', 'user_id', 'achievement_id')->withPivot('count');
    }
    public function countableAchievements($category = null)
    {
        return $this->belongsToMany('App\Achievement', 'hus_achievement_user', 'user_id', 'achievement_id')
                ->withPivot('count')
                ->where('breakpoint', '!=', NULL)
                ->where('category_id', !empty($category) ? '=' : '!=', !empty($category) ? $category : -1)
                ->get();
    }
    public function achievement($achievement)
    {
        return $this->belongsToMany('App\Achievement', 'hus_achievement_user', 'user_id', 'achievement_id')
                ->withPivot('count')
                ->where('id', '=', $achievement->id)
                ->first();
    }
    public function hasAchievement($achievement_id)
    {
        return $this->achievements()->where('id', '=', $achievement_id)->exists();
    }
    public function incrementAchievement($achievement)
    {
        if($achievement->isCountable()){ 
            $achi = $this->achievement($achievement);
            if(!isset($achi->pivot->count)){ $achi->pivot->count = 0; }
            $achievements = $this->achievements();
            if($achi->breakpoint > $achi->pivot->count)
            {
                $achievements->updateExistingPivot($achi->id, ['count' => $achi->pivot->count + 1]);
            }
            return $achievements->where('id', $achi->id)->first();
        }
        return null;
    }
    public function canDisplayAchievementPopup($achievement)
    {
        $hasBreakPoint = !empty($achievement->breakpoint);
        $breakPointReached = $achievement->pivot->count >= $achievement->breakpoint;
        return $hasBreakPoint && $breakPointReached;   
    }
    /**
     * Checks whether user-table has a specific column.
     * @param type $attr
     * @return bool
     */
    public function hasAttribute($attr)
    {
        return array_key_exists($attr, $this->attributes);
    }
}
