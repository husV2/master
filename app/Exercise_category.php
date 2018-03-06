<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use App\Achievement;

class Exercise_Category extends Model
{
    /**
     *
     * The table where this model belongs to
     * 
     * @var string 
     */
    protected $table = 'hus_ex_category';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'type',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
    
    /**
     * 
     * Returns all the exercises that belong to this category.
     * 
     * @return Array(Exercise)
     * @see Category
     */    
    public function exercises()
    {
        return $this->hasMany('App\Exercise', 'ex_category_fk');
    }
    public function achievements()
    {
        return $this->hasMany('App\Achievement', 'category_id');
    }
    /* The achievement in this category that the user is working towards */
    public function currentAchievement($user = null)
    {
        if(empty($user)){ $user = Auth::user(); }
        $achievements = $this->achievements->keyBy('id');
        if($achievements->isEmpty()){ return; }
        $user_achis = $user->countableAchievements($this->id)->keyBy('id');
        $doesntHave = $achievements->diffKeys($user_achis);
        $achi = null;
        /* Check first if the breakpoint has been reached on achis that the user does have */
        foreach($user_achis as $a)
        {
            if($achievements->contains($a) && $a->pivot->count < $achievements->get($a->id)->breakpoint)
            {
                $achi = $a;
                break;
            }
        }
        /* Then attach the first achievement that the user does  not have if he doesn't have any incomplete achievements in this category */
        if(empty($achi) && !$doesntHave->isEmpty())
        {
            $achi = Achievement::find($doesntHave->first()->id);
            $user->achievements()->attach($achi, array('count' => 0));
        }
        return $achi;
    }
}