<?php

namespace App\Helpers;

use App\Exercise_category;
use App\Exercise;
use App\Statistics;
use App\User;
use App\Program;
use URL;
use DB;
use Session;

use Auth;

/**
 * 
 * Handles user data handling for views.
 * 
 */
class UserHelper
{
    public static function setFriendList($friends)
    {
        $returned = array();
        foreach($friends as $friend)
        {
            $avatar = preg_replace('/\s+/', '', $friend->settings->avatar);
            array_push($returned,  array(
                "name" => $friend->username, 
                "img" => empty($avatar) || $avatar === "" ? 'placeholder.png' : $avatar,
                "id" => $friend->id
                ));
        }
        
        return $returned;
    }
    /*
     * Used by MainController to display settings in the user's profile page.
     * 
     * @return array;
     */
    public static function setSettingsList($user)
    {
        $settings = $user->settings;
        
        $ret_settings = array(
            "username" => $user->username,
            "fullName" => $user->firstname." ".$user->lastname,
            "email" => $user->email,
            "group" => $user->group()->name,
            "workhours" => $settings->workhours,
            "motto" => $settings->motto,
            "ex_program" => Program::find($settings->ex_program_fk)->name,
            "event_interval" => $settings->event_interval
            );
        return $ret_settings;
    }
    public static function setCalendarSettingsList($user)
    {
        $settings = $user->settings;
        $ret_settings = array(
            "ex_program" => Program::find($settings->ex_program_fk)->name,
            "event_interval" => $settings->event_interval,
            "generateRandom" => $settings->generateRandom
        ); 
        return $ret_settings;
    }
    /*
     * Used by MainController to remove some fields from guest profiles.
     * 
     * @return array
     */
    public static function addPrivacyToList($settings_list, $fields)
    {
        $censored_list = $settings_list;
        foreach($fields as $field)
        {
            if(isset($censored_list[$field]))
            {
                unset($censored_list[$field]);
            }
        }
        return $censored_list;
    }
    
    /*
     * Used by MainController to get list of user settings for settings change form.
     * 
     * @return array
     */
    public static function setSettingsListForForm($user)
    {
        $settings = $user->settings;
        
        $ret_settings = array(
            "username" => $user->username,
            "firstname" => $user->firstname,
            "lastname" => $user->lastname,
//            "email" => $user->email,
//            "group" => $user->group()->name,
            "workhours" => $settings->workhours,
            "motto" => $settings->motto
            );
        return $ret_settings;
    }
    
    public static function setUserListWithoutFriends($mainUser)
    {
        $friends = $mainUser->friendsAll;
        $friends->prepend($mainUser);
        $friends->all();
        
        $users = UserHelper::filterFriends(User::all(), $friends);
        $returned = array();
        
        foreach($users as $user)
        {
            $avatar = preg_replace('/\s+/', '', $user->settings->avatar);
            array_push($returned, array(
                "id" => $user->id,
                "name" => strtolower($user->username),
                "email" => strtolower(UserHelper::removeLastPartOfEmail($user->email)),
                "img" => empty($avatar) || $avatar === "" ? 'placeholder.png' : $avatar,
                "lname" => strtolower($user->lastname),
                "fname" => strtolower($user->firstname)
             ));
        }
        return $returned;
    }
    private static function filterFriends($users, $friends)
    {
        $diff = $users->diff($friends);

        return $diff->all();
    }
    private static function removeLastPartOfEmail($text)
    {
        list($text) = explode('@', $text);
        $text = preg_replace('/[^a-z0-9]/i', ' ', $text);
        $text = ucwords($text);
        return $text;
    }
    public static function acceptFriendRequest($id)
    {
        $user = Auth::user();
        $relationship = DB::table('hus_friends')
                ->where('user', '=', $id)
                ->where('friend','=', $user->id)
                ->update(['accepted' => true]);
        
        return $relationship > 0;
    }
    public static function declineFriendRequest($id)
    {
        $user = Auth::user();
        $relationship = DB::table('hus_friends')
                ->where('user', '=', $id)
                ->where('friend','=', $user->id)
                ->delete();
        
        return $relationship > 0;
    }
    
    public static function storeProfilePic($image)
    {
        $imgname = str_random(30) . '.' . $image->getClientOriginalExtension();
        $image->move(
              public_path().'/storage/avatars', $imgname
        );
        return $imgname;
    }
    
    public static function removeProfilePic($image)
    {
        return \File::delete(public_path().'/storage/avatars/'.$image);
    }
    
    public static function checkAndSetSessionPic($user = null)
    {
        if(empty($user)){ $user = Auth::user(); }
        
        if (!empty($user) && !Session::has('profilepic')) {
            $avatar = $user->settings->avatar;
            if(empty($avatar) || is_null($avatar))
            {
                $avatar = 'placeholder.png';
            }
            Session::put('profilepic', $avatar);
        }
    }
    public static function getAchievementImgs($user = null)
    {
        if(empty($user)){ $user = Auth::user(); }
        $ret = array();
        $achievements = $user->achievements;
        foreach($achievements as $a)
        {
            array_push($ret, array("img"  => $a->Image(), "title" => $a->name, "desc" => $a->description, "count" => $a->pivot->count, "max" => $a->breakpoint));
        }
        return $ret;
    }

}
