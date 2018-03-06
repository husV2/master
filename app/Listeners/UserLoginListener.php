<?php

namespace App\Listeners;

use App\Helpers\EventHelper;
use App\Settings;
use App\Buddy;
use App\SecurityLog;
use Auth;
use Carbon\Carbon;
use Cache;

class UserLoginListener
{
    protected $user;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        $this->user = Auth::user();
    }

    /**
     * Checks if the exercises are up to date.
     *
     * @return void
     */
    public function onLogin()
    {
        $user = $this->user;
        if(!empty($this->user))
        {
            $this->flushCache();
            $buddy = $user->buddy;
            /* Check whether user account has settings */
            if($user->settings()->count() < 1)
            {
                Settings::createDefault();
            }
            /* Check whether user account has training buddy */
            if(empty($buddy))
            {
                $buddy = Buddy::createDefault();
            }
            $buddy->setStreaks();
            
            
            $firstEvent = EventHelper::checkEvents();
            if(!empty($firstEvent) && !$firstEvent->updated_at->isToday())
            {
                EventHelper::setEventTimes();
            }
            
            $this->clearExpiredPasswordChanges();
        }
        else
        {
            print "Jokin meni pieleen";
        }
    }
    /**
     * Removes cache
     *
     * @return void
     */
    public function onLogout()
    {
        session()->flush();
    }

    /* Cached items in ajaxcontroller and StatisticHelper */
    private function flushCache()
    {
        $user = $this->user;
        foreach(config('personalCache') as $cache)
        {
            $name = $cache.$user->id;
            if(Cache::has($name))
            {
                Cache::forget($name);
            }
        }
    }
    
    private function clearExpiredPasswordChanges()
    {
        $count = 0;
        $logs = $this->user->logs;
        if($logs->isEmpty()){ return; }
        foreach($logs as $log)
        {
           $count += $log->clearExpired();
        }
        return $count;
    }
}