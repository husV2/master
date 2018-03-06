<?php

namespace App\Helpers;

use Auth;

use App\Program;
use App\Event;
use Carbon\Carbon;
use Cache;

/**
 * 
 * Contains the functions that handle the creation and manipulation of exercise programs.
 * 
 */

class EventHelper
{
    
    /**
     * 
     * Tells whether new events have to be generated. Returns the first Event of either already existing events or of newly generated ones.
     * 
     * @return Event::class
     */
    public static function checkEvents()
    {
        $maxDate = Carbon::today()->addDays(14);
        
        $newEvents = Auth::user()->events()->where('start_date', '>', Carbon::today()->toDateTimeString())->get();
        
        if(!$newEvents->isEmpty() && $newEvents->last()->start_date->gte($maxDate))
        {
            return $newEvents->first();
        }
        
        $newestDate = !$newEvents->isEmpty() ? $newEvents->last()->start_date->copy()->addDays(1) : Carbon::now();
        $datediff = $maxDate->diffInDays($newestDate->startOfDay());
        EventHelper::generateEvents($datediff, $newestDate);
        
        return $newEvents->first();
        
    }
    /**
     * 
     * Generates the events for the user.
     * 
     * @return void
     * 
     */
    public static function generateEvents($count, $newestDate)
    {
        $user = Auth::user();
        $interval = $user->settings->event_interval;
        $startDate = $newestDate->copy()->startOfDay();
        $day = $startDate->copy()->dayOfWeek;  


        for($i = 0; $i<$count; $i++)
        { 
            if($day === 7)
            {
                $day = 0;
            }       
            $daily_exercises = Program::find($user->settings->ex_program_fk)->daily($day)->get();
            
            EventHelper::saveNewEvents($startDate->copy(), $daily_exercises, $interval, $user);
            
            $startDate->addDays(1);

            $day++;
        }

        // Give the correct starting times if there were generated events for today
        if($newestDate->isToday())
        {
            EventHelper::setEventTimes();
        }
        
    }
    
    /**
     * 
     * Sets the event times for events, that are active today, using the interval in the user settings.
     * This function is used right after generating new events.
     * 
     * @return void
     */
//    public static function old_setEventTimes()
//    {
//        $now = Carbon::now();
//        $tomorrow = Carbon::tomorrow();
//        $user = Auth::user();
//        $interval = $user->settings->event_interval;
//
//        $events = Event::where('user_fk','=', $user->id)
//                ->where('start_date', '>=', Carbon::today()->toDateTimeString())
//                ->where('start_date', '<', Carbon::tomorrow()->toDateTimeString())
//                ->where('completed', FALSE)
//                ->where('isSkipped', FALSE)
//                ->orderBy('start_date')->withTrashed()->get();
//        
//        foreach($events as $event)
//        {
//            $now->addMinutes($interval);
//            if($now->lt($tomorrow))
//            {
//                $event->start_date = $now->toDateTimeString();
//                $event->save();
//            }
//            else
//            {
//                $event->delete();
//            }
//        }
//        
//    }
    
     /**
     * 
     * Sets the event times for events. If the time change is because of interval change, newInterval and oldInterval need to be set.
     * 
     * @return void
     */
    public static function setEventTimes($newInterval = null, $oldInterval = null, $user = null)
    {
        $now = Carbon::now();
        $tomorrow = Carbon::tomorrow();
        $noIntervalChange = empty($newInterval) || empty($oldInterval);
        if(empty($user))
        {
            $user = Auth::user();
        }
        $events = Event::where('user_fk','=', $user->id)
                ->where('start_date', '>=', $noIntervalChange ? Carbon::today()->toDateTimeString() : Carbon::now()->toDateTimeString())
                ->where('start_date', '<', Carbon::tomorrow()->toDateTimeString())
                ->where('completed', FALSE)
                ->where('isSkipped', FALSE)
                ->orderBy('start_date')->withTrashed()->get();
        // If something went wrong
        if($events->count() < 1)
        {
            return;
        }
        $interval = $noIntervalChange ? $user->settings->event_interval : $newInterval;
        
        // If event times are not set by changing the settings (i.e. No newInterval or oldInterval)
        if($noIntervalChange)
        {
            $now->addMinutes($interval);
        }
        else
        {
            $timeDiff = $events->first()->start_date->diffInSeconds($now);
            $intDiff = ($newInterval - $oldInterval)*60;
            $now->addSeconds($timeDiff + $intDiff);
        }
        foreach($events as $event)
        {
            if($now->lt($tomorrow))
            {
                if($event->trashed())
                {
                    $event->restore();
                }
                $event->start_date = $now;
                $event->save();
            }
            else
            {
                $event->delete();
            }
            $now->addMinutes($interval);
        }
        
    }
    
    /**
     * 
     * Remove events that haven't been completed.
     * 
     * @return Illuminate\Database\Eloquent\Collection
     * 
     */
    public static function removeEvents()
    {
        $events = Event::where('user_fk','=', Auth::user()->id)
                ->where('start_date', '>=', Carbon::now()->toDateTimeString())
                ->where('completed', '=', FALSE)
                ->where('isSkipped', '=', FALSE)
                ->withTrashed()->forceDelete();
        
        return $events;
    }
    
    /*
     * Determines whether user has ran out of events and whether new random one needs to be generated.
     * If the the above is true, it uses generateRandom-method to generate one random event.
     * Uses generateRandom-field in the user setting. User can choose whether they want random
     * events to be generated.
     * 
     * @return
     */
    public static function eventsExist($user, $user_settings = null)
    {
        if(empty($user)) { return false; }
        $settings = empty($user_settings) ? $user->settings : $user_settings;
        if(!$settings->generateRandom) { return false; }
        $events = Event::where('start_date', '>', Carbon::now()->toDateTimeString())
                ->where('start_date', '<', Carbon::tomorrow()->toDateTimeString())
                ->where('user_fk', '=', $user->id)
                ->where('completed', '=', FALSE)
                ->where('isSkipped', '=', FALSE)
                ->count();
        if($events > 0) { return false; }
        return EventHelper::generateRandom($user, $settings);
    }
    
    
    /********* PRIVATE FUNCTIONS **********/
    
    /*
     * Generate one random event for user. Used by getNext and getEvents in AjaxController.
     * 
     * @return 
     * 
     */
    private static function generateRandom($user, $settings = null)
    {
        if(empty($settings)){ $settings = $user->settings; }
        $program = Program::find($settings->ex_program_fk);
        if(empty($program)){ return false; }
        $exercises = $program->exercises;
        $interval = $settings->event_interval;
        $exercise = $exercises->random();
        $cat = $exercise->category;
        $event = Event::create([
            'user_fk' => $user->id, 
            'ex_fk' => $exercise->id,
            'start_date' => Carbon::now()->addMinutes($interval)
        ]);
        $cacheName = config('personalCache.events').$user->id;
        if (Cache::has($cacheName)){
            $events = json_decode(Cache::pull($cacheName));
            array_push($events, array(
                        "name" => $exercise->name, 
                        "duration" => $exercise->duration, 
                        "interval" => $interval, 
                        "category"=> $cat->name, 
                        "color" => $cat->color, 
                        "start_date" => $event->start_date->toDateTimeString(), 
                        "isCompleted" => $event->completed, 
                        "description" => $exercise->description
                    ));
            Cache::put($cacheName, json_encode($events), Carbon::tomorrow()->diffInMinutes(Carbon::now()));
        }
        return true;
    }
    /**
     * Used by generateEvents -method to create new events for a specific day
     * 
     * @return void
     * 
     */
    private static function saveNewEvents($dailyTime, $daily_exercises, $interval, $user = null)
    {   
        $time = $dailyTime->copy();
        $tomorrow = $dailyTime->copy()->addDays(1);
        if(empty($user))
        {
            $user = Auth::user();
        }

        foreach($daily_exercises as $exercise)
        {
            if($time->lt($tomorrow))
            { 
                $event = new Event();
                $event->user_fk = $user->id;
                $event->ex_fk = $exercise->id;
                $event->completed = false;
                $event->start_date = $time->toDateTimeString();
                $event->save();

                $time->addMinutes($interval);
            }
        }
    }

   
}
