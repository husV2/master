<?php

namespace App\Helpers;

use App\Exercise_category;
use App\Exercise;
use App\Statistics;
use App\Buddy;
use DB;
use Auth;
use Cache;
use App\Helpers\GroupHelper;
use App\Helpers\CacheHelper;
use Carbon\Carbon;

/**
 * 
 * Handles everything that has to do with statistics.
 * 
 */
class StatisticsHelper
{
    /* The time for which items are kept in the cache */
    private $cacheTime = 10;
    private function cache($key)
    {
        if (Cache::has($key)) {
            return Cache::get($key);
        }
        return false;
    }
    /**
     * 
     * Returns the stats for personal accomplishments -chart which displays
     * the favourite exercise categories as a pie chart. In the future, this could only show events for one month to prevent
     * database from bloating.
     * 
     * @return json
     */
    public function personalStats($user)
    {
        $cachename = config('personalCache.personalStats').$user->id;
        $cache = $this->cache($cachename);
        if(!empty($cache))
        {
            return $cache;
        }
        $events = $user->events()->get();
        $categories = Exercise_category::all();
        $arr = array();
        
        foreach($categories as $cat)
        {
            $arr[$cat->name]["score"] = 0;
            $arr[$cat->name]["color"] = $cat->color;
        }
        
        foreach($events as $event)
        {
            if($event->completed)
            {
                $category = Exercise_category::find(Exercise::find($event->ex_fk)->ex_category_fk);
                $arr[$category->name]["score"] += 1;
            }
        }
        $encoded = json_encode($arr);
        Cache::put($cachename, $encoded, $this->cacheTime);
        
        return $encoded;
    
    }
    /**
     * 
     * Returns the department ranking for user. Takes in variable which determines the level of the department 
     * (hus_group_1, hus_group_2...etc).
     * 
     * @return array
     */
    public function departmentRanking($level)
    {
        $c = new CacheHelper();
        $ranks = $c->getCompletesOrSkipped($level, true);
        $ranks = collect($ranks[$level])->sortByDesc("value");
//        if(!empty($cache))
//        {
//            return $cache;
//        }
//        if($level < 1 || $level > 2)
//        {
//            return;
//        }
        
//        $statistics = new Statistics($level);
//
//        $stats = $statistics->skipCompleteRelation();
        
        $var = 'hus_group_'.$level.'_id';
        $department = Auth::user()->$var;
        if(empty($department)){ return array(); }
        $rank = 1;
        foreach($ranks as $id => $val)
        {
            if($id === $department){ break; }
            $rank++;
        }
        $returned = array("max"=> count($ranks), "ranking" => $rank);
        return $returned;
    }
    
    /**
     * 
     * Gives the amount of completed events for each friend.
     * 
     * @return json
     */
    public function friend_activity()
    {
        $user = Auth::user();
        $cachename = config('personalCache.friendActivity').$user->id;
        $cache = $this->cache($cachename);
        if(!empty($cache))
        {
            return $cache;
        }
        $friends = $user->friends;
        $arr = array();
        
        foreach($friends as $friend) {
            $check = $friend->firstname;
            if (empty($check)) {
                $arr[$friend->username]= $friend->completed->count();
            } else {
                $arr[$friend->firstname.' '.$friend->lastname[0].'.']= $friend->completed->count();
            }
        }
        $encoded = json_encode($arr);
        Cache::put($cachename, $encoded, $this->cacheTime);
        return $encoded;
    }
    
    /**
     * 
     * Gives the weekly activity of the user. Activity is determined by the ratio of completed to all daily events.
     * 
     * @return json - Contains the ratio for each day. Ratio is a percentage of completed events ( ex. 80 )
     * 
     */
    public function weekly_activity()
    {
        $user = Auth::user();
        $cachename = config('personalCache.weeklyActivity').$user->id;
        $cache = $this->cache($cachename);
        if(!empty($cache))
        {
            return $cache;
        }
        $day = date("Y-m-d H:i:s", strtotime('monday this week'));
        $arr = array();
        
        for($i = 0; $i<7; $i++)
        {
            $events= $user->events()
                    ->where('start_date', '>=', $day)
                    ->where('start_date', '<', date('Y-m-d H:i:s', strtotime($day . ' +1 day')))
                    ->get();
            $completed = $events->where('completed', '1')->count();
            $total = $events->count();
            $arr[trans('datetime.day_'.$i)] = $total === 0 ? 0 : ($completed/$total)*100;
            
            $day = date('Y-m-d H:i:s', strtotime($day . ' +1 day'));
        }
        $encoded = json_encode($arr);
        Cache::put($cachename, $encoded, $this->cacheTime);
        
        return $encoded;
    }
    
    public static function buddyHP($user)
    {    
        $buddy = $user->buddy;
        /* Check whether user account has training buddy */
        if(empty($buddy))
        {
            $buddy = Buddy::createDefault($user);
        }
        return array("maxHP" => $buddy->maxHP, "HP" => $buddy->health);
    }
    public static function loginStreak($user)
    {    
        $buddy = $user->buddy;
        /* Check whether user account has training buddy */
        if(empty($buddy))
        {
            $buddy = Buddy::createDefault($user);
        }
        return array("streak" => $buddy->login_streak, "best" => $buddy->best_login_streak);
    }
    /*
     * Displays global statistics for user. 
     * How many completed/skipped events in total and how long has the user spent when their durations are summed.
     * If cache doesn't exist new cache entry will be created from existing data (NOTE! In the future we might need to delete old event entries
     * so it's important to keep this cache forever)
     */
    public static function totalStats($user = null)
    {
        if(!$user){ $user = Auth::user(); }
        $cacheName = config('personalCache.globalStats').$user->id;
        $cache = $this->cache($cacheName);
        if(!empty($cache)){ return $cache; }
        $completed = $user->completed;
        $completeCount = $completed->count();
        $skipCount = $user->incompleteEvents->count();
        $totalTime = $completed->sum('duration');
        Cache::forever($cacheName, json_encode(array("completed" => $completeCount, "skipped" => $skipCount, "totalTime" => $totalTime)));
    }
    /**
     * Gets the exercise ids of completed/skipped events for a specific group. (Default: completed)
     * Returns an array of ids if level exists, false otherwise.
     * @param type $level
     * @param type $group_id
     * @param type $completes
     * @return boolean
     */
    public static function completesOrSkipped($level, $group_id, $completes = TRUE)
    {
        if(!GroupHelper::exists($level))
        {
            return false;
        }
        $column = $completes ? "completed" : "isSkipped";
        $users = GroupHelper::usersForGroup($level, $group_id);
        return array_map(function($x){ return $x->ex_fk; }, DB::table('hus_ex_event')
                ->select('ex_fk')
                ->whereIn('user_fk', $users)
                ->where($column, '=', TRUE)
                ->get());
    }
    /**
     * Returns the total used time on exercises for a specific group.
     * 
     * @param type $level
     * @param type $group_id
     * @return boolean
     */
    public static function usedTime($level, $group_id)
    {
        if(!GroupHelper::exists($level))
        {
            return false;
        }
        return floatval(DB::table('hus_exercise')
                 ->select(DB::raw('sum(duration) as "time"'))
                 ->whereIn('id', StatisticsHelper::completesOrSkipped($level, $group_id))
                 ->First()->time);
    }
//    /*
//     * Sets up the global cache using the current data in the database.
//     * NOTICE! THIS FUNCTION SHOULD BE CALLED ONLY AT FIRST SETUP.
//     * 
//     * @return void
//     */
//    public function setGlobalCache()
//    {
//        $g = "globalCache";
//        $keys = array_keys(config($g));
//        /* We start from group 3 since group 1 and 2 have their own database tables */
//        $index = 3;
//        while(!empty($groups = GroupHelper::all($index)))
//        {
//            foreach($groups as $group)
//            {
//                StatisticsHelper::setGlobalCacheForGroup($g, $keys, $group);
//            }
//            $index++;
//        }
//    }
//    /* Used by setGlobalCache-function in this class
//     * 
//     * @return void
//     */
//    private function setGlobalCacheForGroup($g, $keys, $group)
//    {
//        foreach($keys as $key)
//        {
//            $cachename = config($g.'.'.$key).$group->id;
//            //Cache::forever($cacheName, );
//        }
//    }
        

}
