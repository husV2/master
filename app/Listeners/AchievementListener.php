<?php

namespace App\Listeners;

use App\Events\ProfileUpdate;
use App\Events\AvatarChange;
use App\Events\AddFriend;
use Auth;
use App\Achievement;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class AchievementListener
{
    protected $user;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        if(!Auth::guest())
        {
            $this->user = Auth::user();
        }
    }
    public function handle($event)
    {
        if($this->user->hasAchievement($event->id))
        {
            $this->checkForProfileCreated($event);
            return false;
        }        
        $achievement = Achievement::find($event->id);
        $this->user->achievements()->attach($achievement);
        $this->checkForProfileCreated($event);
        return $achievement;
    }
    private function checkForProfileCreated($event)
    {
        if(!$this->user->hasAchievement(5) && ($event->id === 1 || $event->id === 2))
        {
            $this->profileCreated();
        }
    }
    private function profileCreated()
    {
        if($this->user->hasAchievement(1) && $this->user->hasAchievement(2))
        {
            $achievement = Achievement::find(5);
            $this->user->achievements()->attach($achievement);
        }
    }
}
