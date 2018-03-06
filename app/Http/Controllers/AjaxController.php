<?php
namespace App\Http\Controllers;

use Validator;
use Redirect;
use App\Http\Requests;
use Illuminate\Http\Request;
use Auth;
use App\Events\AvatarChange;
use App\Events\ProfileUpdate;
//use App\Events\EventCompletedOrSkipped;
use App\Events\AddFriend;
use URL;
use App\Exercise;
use App\Exercise_category;
use App\Event;
use App\User;
use App\Statistics;
use App\SecurityLog;
use App\Helpers\EventHelper;
use App\Helpers\GroupHelper;
use App\Helpers\UserHelper;
use App\Helpers\CacheHelper;
use App\Survey;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use Cache;

/**
 * 
 * Handles all the ajax requests. The routes that use these functions can be found
 * in app/routes.php file. Requests are authorized in the __construct function.
 * 
 */
class AjaxController extends Controller
{
    
    /**
     * Create a new controller instance.
     * Authenticates the user before any page, that this controller controls, is displayed
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('verify');
    }
    
    /*
     * Returns the subdivisions for an organization.
     * 
     * @return JSON
     */
    public function getSubdivisions(Request $req)
    {
        $id  = $req->input('id');
        $nextLevel = $req->input('level');
        
        if($id === null || $nextLevel === null || $id === 0 || $nextLevel === 0 || $nextLevel === 1 || $nextLevel > 4)
        {
            return json_encode(array("error" => "Invalid values given"));
        }
        $subdivisions = GroupHelper::subdivisions($nextLevel-1, $id);
        
        if(count($subdivisions) < 1)
        {
            return json_encode(array("error" => "Failed to get subdivisions for group ".$nextLevel-1));
        }
        
        $returnArr = array();
        
        foreach($subdivisions as $sub)
        {
            $returnArr[$nextLevel."_".$sub->id] = $sub->name;
        }
        
        return count($returnArr) > 0 ? json_encode($returnArr) : 0;
    }
    
    /*
     * Gets all the event that the user has and returns them as json array.
     * 
     * @return JSON
     */
    public function events()
    {
        $user = Auth::user();
        if(empty($user))
        {
            return "Error when fetching user events";
        }
        $cacheName = config('personalCache.events').$user->id;
        if (Cache::has($cacheName)){
            return Cache::get($cacheName);
        }
        $eventList = $user->events;
        
        $returnedArr = array();
        
        
        foreach($eventList as $event)
        {
            $exercise = Exercise::find($event->ex_fk);
            $cat = $exercise->category()->first();
            
            if($exercise == null)
            {
                return "Something went wrong";
            }
            array_push($returnedArr, 
                    array(
                        "name" => $exercise->name, 
                        "duration" => $exercise->duration, 
                        "interval" => $user->settings->event_interval, 
                        "category"=> $cat->name, 
                        "color" => $cat->color, 
                        "start_date" => $event->start_date->toDateTimeString(), 
                        "isCompleted" => $event->completed, 
                        "description" => $exercise->description
                    )
            );
        }
        $encoded = json_encode($returnedArr);
        /* Cache the results for one day. Cache is flushed if exercise program is modified */
        Cache::put($cacheName, $encoded, Carbon::tomorrow()->diffInMinutes(Carbon::now()));
        return $encoded;
    }
    
    
    /*
     * Gets the next event. Used by the timer.
     * 
     * Returns null when there's no events for this day.
     * Returns JSON array containing the details of the next event otherwise.
     */  
    public function getNextEvent()
    {
        $user = Auth::user();
        $time = time();
        
        if(empty($user)){ return "User is not logged in!"; }
        $refetch = EventHelper::eventsExist($user);
        $event = Event::where('user_fk','=', $user->id)
                ->where('start_date', '>', date("Y-m-d H:i:s", $time))
                ->where('start_date','<', date("Y-m-d H:i:s", strtotime("+ 1 days ", $time)))
                ->where('completed', FALSE)
                ->where('isSkipped', FALSE)
                ->orderBy('start_date')->first();
        
        if(empty($event)){ return null; }
        $exercise = Exercise::find($event->ex_fk);
        $category = Exercise_category::find($exercise->ex_category_fk);
        $data = array(
            "id" => $event->id,
            "name" => $exercise->name,
            "start_date" => $event->start_date->toDateTimeString(),
            "html" => $exercise->content_html,
            "duration" => $exercise->duration,
            "category" => $category->name,
            "category_type" => $category->type,
            "refetch" => $refetch
        );
        return json_encode($data);
    }
    
    /*
     * Postpones the event.
     * 
     * @return string - event start time in string format
     */
    public function snooze(Request $req)
    {
        $event = Event::find($req->input('id'));
        
        if($event == null)
        {
            return "Failed to find an event with that id";
        }
        
        $start_time = $event->start_date->addMinutes(15);
        
        $event->start_date = $start_time;
        $event->save();
        
        return $start_time->toDateTimeString();
    }    
    /*
     * Marks the event as skipped.
     * 
     * @return string
     */
    public function skipEvent(Request $req)
    {
        $event = Event::find($req->input('id'));
        $user = Auth::user();
        
        if($event == null)
        {
            return "Failed to find an event with that id";
        }
        if($event->user->id !== $user->id){ return "Logged in user does not own this event!"; }
        $i = 1;
        $column = 'hus_group_'.$i.'_id';
        $g = GroupHelper::forUser($user, $i);
        $c = new CacheHelper();
        while($user->hasAttribute($column) && !empty($g))
        {
            $c->incrementGlobalCompleteOrSkipped($i, $g, false);
            $i++;
            $column = 'hus_group_'.$i.'_id';
            $g = GroupHelper::forUser($user, $i);
        }
        $cacheName = config('personalCache.globalStats').Auth::user()->id;
        if (Cache::has($cacheName)){
            $oldStats = json_decode(Cache::pull($cacheName));
            $oldStats["skipped"] = intval($oldStats["skipped"]) + 1;
            Cache::forever($cacheName, json_encode($oldStats));
        }
        $event->isSkipped = TRUE;
        $event->save();
        
        return "Event skipped";
    }
    
    
    /*
     * Completes an event.
     * 
     * @return string
     */
    public function completeEvent(Request $req)
    {
        $user = Auth::user();
        $buddy = $user->buddy;
        $event = Event::find($req->input('id'));
        $returned = array("message" => "Event completed!");
        $c = new CacheHelper();
        if(empty($event)){ return json_encode(array("message" => "Couldn't find an event with the given id")); }
        
        $duration = Exercise::find($event->ex_fk)->duration;
        $i = 1;
        $column = 'hus_group_'.$i.'_id';
        $g = GroupHelper::forUser($user, $i);
        while($user->hasAttribute($column) && !empty($g))
        {
            $c->incrementGlobalCompleteOrSkipped($i, $g, true);
            /* This is the old system (inside if) which might be removed in the future */
//            if($i > 0 && $i < 3)
//            {
//                $group = new Statistics();
//                $group->setTable('hus_statistics_'.$i);
//                $group = $group->get();
//                $group = $group->find($user->$column);
//                $group->setTable('hus_statistics_'.$i);
//                $group->completes++;
//                $group->timeSpent += $duration;
//                $group->save();
//            }
            $i++;
            $column = 'hus_group_'.$i.'_id';
            $g = GroupHelper::forUser($user, $i);
        }
        $buddy->exercise_streak++;
        $buddy->save();
        
        $cacheName = config('personalCache.globalStats').$user->id;
        if (Cache::has($cacheName)){
            $oldStats = json_decode(Cache::pull($cacheName));
            $oldStats["completed"] = intval($oldStats["completed"]) + 1;
            $oldStats["totalTime"] = floatval($oldStats["totalTime"]) + $duration;
            Cache::forever($cacheName, json_encode($oldStats));
        }
        
        $event->completed = TRUE;
        $event->complete_date = date("Y-m-d H:i:s", time());
        $event->save();
        Cache::forget("personalStats_".$user->id);
        
        /* Finally, increment the achievement for user */
        $achi = $event->exercise->category->currentAchievement($user);
        if(!empty($achi)){ $achi = $user->incrementAchievement($achi); }
        if(!empty($achi) && $user->canDisplayAchievementPopup($achi))
        {
            $returned["achievement"] = $achi;
        }
        
        return json_encode($returned);
    }
    
    public function sendFriendRequest(Request $req)
    {
        $user = Auth::user();
        $errors = array(
            "id" => $req["friend_id"]
        );
        
        if(!$user->sendFriendRequest($req["friend_id"]))
        {
            array_push($errors, array("errors" => "Failed to send friend request"));
        }
        else
        {
            if(!$user->hasAchievement(3))
            {
                $achievement = event(new AddFriend($req["friend_id"]));
                $errors["achievement"] = $achievement;
            }
        }
        
        return json_encode($errors);
    }
    public function acceptFriendRequest($id)
    {
        return back()->with('success', UserHelper::acceptFriendRequest($id));
    }
    public function declineFriendRequest($id)
    {
        return back()->with('success', UserHelper::declineFriendRequest($id));
    }
    public function confirmPasswordChange($token)
    {
        $log = SecurityLog::findWithToken($token);
        if(empty($log))
        {
            return \View::make('auth.verify')->with('data', ["msg" => trans('auth.password_revert_token_not_found'), "mailSent" => true]);
        }
        $user = $log->user;
        $user->password = $log->new;
        $user->save();
        $log->clear();
        return \View::make('auth.verify')->with('data', ["msg" => trans('auth.password_change_success'), "mailSent" => true]);
    }
	
	public function submitSurvey(Request $req) {
		if (!Survey::canFill()) return;
		$value = $req["vitalityValue"];
		if (!$value) return;
		$survey = new Survey();
		$survey->user_fk = Auth::id();
		$survey->vitality_state = $value;
		$survey->save();
		return json_encode(array("success" => "Survey has been sent."));
	}
    
}