<?php

namespace App\Http\Controllers;

use Hash;
use Mail;
use Validator;
use Redirect;
use App\Http\Requests;
use Illuminate\Http\Request;
use Auth;
use App\Events\AvatarChange;
use URL;
use App\Exercise;
use App\Exercise_category;
use App\Event;
use App\User;
use App\Statistics;
use App\SecurityLog;
use App\Helpers\EventHelper;
use App\Helpers\UserHelper;
use Carbon\Carbon;
use Cache;
use App\Events\ProfileUpdate;
/*
 * 
 * Handles the ajax calls for different user settings changes.
 * 
 */
class SettingsController extends Controller
{
     /**
     * Create a new controller instance.
     * Authenticates the user before any page, that this controller controls, is displayed
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth', 'verify']);
    }
        /*
     * Change the user settings.
     * 
     * Finally, redirects to the profile page.
     * 
     * @return redirect
     */
    public function handleSettingsChange(Request $req)
    {
        $user = Auth::user();
        $settings = $user->settings;
        $validator = $this->validator($req->all()); 

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }
        foreach($user->fields as $field)
        {
            $user->$field =  $req->has($field) && !empty($req[$field]) ? htmlspecialchars($req[$field], ENT_QUOTES, 'UTF-8') : $user->$field;
        }
        
        foreach($settings->fields as $field)
        {
            $settings->$field = $req->has($field) && !empty($req[$field]) ? htmlspecialchars($req[$field], ENT_QUOTES, 'UTF-8') : $settings->$field;
        }
        $user->save();
        $settings->save(); 
        $achievement = event(new ProfileUpdate()); 
        if(!empty($achievement))
        {
            return redirect('profile')->with('achievement', $achievement);
        }
        return redirect('profile');
    }
    public function handleCalendarSettingsChange(Request $req)
    {
        $user = Auth::user();
        $settings = $user->settings;
        $oldValues = array(
            "program" => $settings->ex_program_fk,
            "interval" => $settings->event_interval,
            "generate" => $settings->generateRandom
        );
        $validator = $this->validatorForCalendar($req->all());
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator);
        }
        foreach($settings->fields as $field)
        {
            if($field === "generateRandom")
            {
                $settings->$field = ($req->has($field) && !empty($req[$field]));
            }
            else {
                $settings->$field = $req->has($field) && !empty($req[$field]) ? htmlspecialchars($req[$field], ENT_QUOTES, 'UTF-8') : $settings->$field;
            }
        }
        $isChanged = array(
            "interval" => $oldValues["interval"] !== $settings->event_interval, 
            "program" => $oldValues["program"] !== $req["ex_program_fk"],
            "generate" => $oldValues["generate"] !== $settings->generateRandom
                );
        $flushCache = $isChanged["interval"] || $isChanged["program"];
        if($isChanged["interval"] || $isChanged["program"] || $isChanged["generate"]){ $settings->save(); }
        foreach($isChanged as $key => $value)
        {
            if($value)
            {
                switch($key)
                {
                    case "interval":
                        EventHelper::setEventTimes(intval($req["event_interval"]), $oldValues["interval"]);
                        break;
                    case "program":
                        EventHelper::removeEvents();
                        EventHelper::generateEvents(14, Carbon::now()); 
                        break;
                }
            }
        }
        if($flushCache)
        {
            Cache::forget("events_".$user->id);
        }
        return redirect('/');
    }
    
    public function handleProfilepicChange(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'profilepic' => 'required|image|max:5000'
        ]);

        if ($validator->fails()) {
            return back()
                ->with('profilepic_modal_open', true)
                ->withErrors($validator)
                ->withInput();
        }
        if($req->hasFile('profilepic'))
        {
            $name = UserHelper::storeProfilePic($req['profilepic']);
            $settings = Auth::user()->settings;
            UserHelper::removeProfilePic($settings->avatar);
            $settings->avatar = $name;
            $settings->save();
            $req->session()->forget('profilepic');
            $req->session()->put('profilepic', $name);
            $achievement = event(new AvatarChange($req['profilepic']));
        }
        if(!empty($achievement))
        {
            return back()->with('achievement', $achievement);
        }
        return back();
    }
    
    public function confirmOldPassword(Request $req)
    {
        $pw = empty($req["old"]) ? $req["data"]["old"] : $req["old"];
        if (Hash::check($pw, Auth::user()->password))
        {
            return response()->json(["success" => trans('main.password_ok')], 200);
        }
        else
        {
            return response()->json(["error" => trans('main.wrong_password')], 403);
        }
    }
    public function changePassword(Request $req)
    {
        $confirmation = $this->confirmOldPassword($req);
        if($confirmation->status() != 200)
        {
            return $confirmation;
        }

        $validator = $this->validatorForPwd($req["data"]);
        if ($validator->fails()) {
            return response()->json(array("error" => $validator->errors()), 400);
        }
        $user = Auth::user();
        if(Hash::check($req["data"]["password"], $user->password))
        {
            return response()->json(array("error" => trans('main.identical_password')), 400);
        }
        $log = new SecurityLog;
        $log->user_id = $user->id;
        $log->ip = $req->ip();
        $log->new = bcrypt($req["data"]["password"]);
        $log->token = uniqid (str_random(40));
        $log->save();
        $this::sendPasswordChangeMail($user->email, $user->username, $log);
        return response()->json(array("success" => trans('main.password_changed')), 200);
    }
    
    /******** PASSWORD CHANGE VALIDATION ********/
    /*
     * Sends the verification email to the user whenever password is changed.
     * 
     * @return boolean
     */
    protected static function sendPasswordChangeMail($email, $username, SecurityLog $log)
    {
        $data = ['token' => $log->token, 'ip' => $log->ip, 'time' => $log->created_at, 'username' => $username, 'email' => $email];
        
        Mail::send('auth.emails.password_change', $data, function($message) use ($data) {
            $subject = trans('auth.password_revert_title');
            $message->to($data["email"], $data["username"])
                ->subject($subject);
        });
        if(count(Mail::failures()) > 0){
            return false;
        }
        
        return true;
    }
    
    /******** VALIDATORS *************/
    protected function validator(array $data)
    {
        $user = Auth::user();
        return Validator::make($data, [
            'username' => 'required|max:60|min:1|unique:hus_user,username,'.$user->id.',id',
            'firstname' => 'max:255',
            'lastname' => 'max:255',
            'workhours' => 'integer|max:24',
            'event_interval' => 'numeric|min:1|max:180',
            'motto' => 'max:255'
        ]);
    }
    protected function validatorForCalendar(array $data)
    {
        return Validator::make($data, [
            'ex_program' => 'exists:hus_ex_program,id',
            'event_interval' => 'numeric|min:10|max:180',
        ]);
    }
    protected function validatorForPwd(array $data)
    {
        return Validator::make($data, [
            'password' => 'required|confirmed|min:6'
        ]);
    }
}