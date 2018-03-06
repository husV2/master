<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Survey;
use App\AdminNotification;
use App\HealthInfo;
use Auth;

class Buddy extends Model
{
    /**
     *
     * The table where this model belongs to
     * 
     * @var string 
     */
    protected $table = 'hus_buddy';
    
    public $maxHP = 30;
    public $minHP = 0;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'owner_id', 'health', 'ex_streak_date', 'exercise_streak', 'login_streak', 'best_login_streak'
    ];
    protected $dates = [
        'created_at',
        'updated_at',
        'ex_streak_date'
    ];
    /**
     * The default values for the model.
     * 
     * @var array
     */
    protected $attributes = array(
        'health'  => 7
    );
    
    /**
     * 
     * Returns the user who owns this buddy.
     * 
     * @return User
     * @see User
     */    
    public function user()
    {
        return $this->belongsTo('App\User', 'owner_id');
    }
    public static function createDefault($user = NULL)
    {
        $buddy = new Buddy();
        $buddy->owner_id = empty($user) ? Auth::user()->id : $user->id;
        $buddy->ex_streak_date = Carbon::today();
        $buddy->login_streak = 1;
        $buddy->save();
        
        return $buddy;   
    }
    public function setStreaks()
    {
        $addedHealth = 0;
        if(empty($this->ex_streak_date)){ $this->ex_streak_date = Carbon::today(); }
        if($this->updated_at->isYesterday() && $this->ex_streak_date->isYesterday()){ $this->login_streak++; }
        else if(!$this->updated_at->isToday()){ $this->login_streak = 1; }
        if(!$this->ex_streak_date->isToday())
        {
            $addedHealth = $this->adjustHealth();
            $this->exercise_streak = 0;
            $this->ex_streak_date = Carbon::today();
        }
        if($this->login_streak > $this->best_login_streak){ $this->best_login_streak = $this->login_streak; }
        $this->save();
        
        //TODO: do something with this. For example, inform the user about their success or failure
        return $addedHealth;
    }
    
    private function adjustHealth()
    {
        $addedHealth = 0;
        if($this->exercise_streak >= 2)
        {
            $addedHealth++;
        }
        else if($this->exercise_streak >= 4)
        {
            $addedHealth = 2;
        }
        else
        {
            $addedHealth = -1;
        }
        $this->health += $addedHealth;
        
        /* Makes sure the health doesn't go over or under the set health limits */
        $this->health = $this->health > $this->maxHP ? $this->maxHP : $this->health;
        $this->health = $this->health < $this->minHP ? $this->minHP : $this->health;
        
        return $addedHealth;
    }
    
    /* Creates the buddy for the front end */
    public function view()
    {
        $view = view('training_buddy');
        $image = 'img/apuri_normal.gif';
        if($this->health < 5)
        {
            $image = 'img/apuri_nyyh.gif';
        }
        else if($this->health > 20)
        {
            $image = 'img/apuri_muskeli.gif';
        }
        
        return $view->with('image', $image)
					->with('survey_fillable', Survey::canFill())
					->with('admin_notifications', AdminNotification::getNotifications())
					->with('health_infos', HealthInfo::getActiveInfos());
    }
    

}