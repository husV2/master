<?php

namespace App\Helpers;

use Auth;

use App\Program;
use App\Event;
use Carbon\Carbon;
use Cache;

class CacheHelper
{
    /**
     * Returns and caches (if not yet cached) the completed or skipped (default: completed) events for all groups at specific level.
     * Also accepts an array of levels.
     * 
     * @param type $level
     * @param type $completed
     * @return array
     */
    public function getCompletesOrSkipped($level, $completed = TRUE)
    {
        if(!is_array($level)){ $level = array($level); }
        $column = $completed ? "completed" : "skipped";
        $returned = array();
        foreach($level as $l)
        {
            $groups = GroupHelper::all($l);
            foreach($groups as $group)
            {
                $cacheName = config('globalCache.'.$column).$l.'_'.$group->id;
                //Cache::forget($cacheName);
                if(Cache::has($cacheName) && !empty($cache = Cache::get($cacheName)))
                {  
                    $returned[$l][$group->id] = $cache;
                }
                else
                {
                    $count = count(StatisticsHelper::completesOrSkipped($l, $group->id, $completed));
                    Cache::forever($cacheName, array("value" => $count, "updated_at" => Carbon::now(), "name" => $group->name));
                    $returned[$l][$group->id] = Cache::get($cacheName);
                }
            }
        }
        return $returned;
    }
    /**
     * Returns and caches (if not yet cached) the time spent on exercises for each group at specific level.
     * Also accepts an array of levels.
     * @param type $level
     * @return array
     */
    public function getUsedTime($level)
    {
        if(!is_array($level)){ $level = array($level); }
        $returned = array();
        foreach($level as $l)
        {
            $groups = GroupHelper::all($l);
            foreach($groups as $group)
            {
                $cacheName = config('globalCache.usedTime').$l.'_'.$group->id;
                //Cache::forget($cacheName);
                if(Cache::has($cacheName) && !empty($cache = Cache::get($cacheName)))
                {
                    $returned[$l][$group->id] = $cache;
                }
                else {
                    $time = StatisticsHelper::usedTime($l, $group->id);
                    Cache::forever($cacheName, array("value" => $time, "updated_at" => Carbon::now()));
                    $returned[$l][$group->id] = Cache::get($cacheName)["value"];
                }
            }
        }
        return $returned;
    }
    
//    public function refreshDepartmentRanking($level)
//    {
//        if(!is_array($level)){ $level = array($level); }
//        $returned = array();
//        $completes = $this->getCompletesOrSkipped($level, true);
//        $n = config("departmentRanking");
//        foreach($level as $l)
//        {
//            $cacheName = $n.$l;
////            if(Cache::has($cacheName) && !empty($cache = Cache::get($cacheName)))
////            {
////                $returned[$l] = $cache;
//////            }
////            else
////            {
//            Cache::forget($cacheName);
//            Cache::forever($cacheName, collect($completes[$l]));
//            $returned[$l] = Cache::get($cacheName);
////            }
//            
//        }
//        return $returned;
//    }
    public function incrementGlobalCompleteOrSkipped($level, $group, $complete = true)
    {
        if(empty($group)){ echo $group[0]; }
        if(!is_numeric($level)){ return false; }
        $column = $complete ? "completed" : "skipped";
        $cacheName = config('globalCache.'.$column).$level.'_'.$group->id;
        if(Cache::has($cacheName) && !empty($cache = Cache::get($cacheName)))
        {
            $cache["value"] = intval($cache["value"]) + 1;
            $cache["updated_at"] = Carbon::now();
            Cache::forget($cacheName);
            Cache::forever($cacheName, $cache);
            $n = config("departmentRanking");
            Cache::forget($n.$level);
            return true;
        }
        return false;
    }

}