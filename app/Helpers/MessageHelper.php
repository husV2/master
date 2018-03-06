<?php

namespace App\Helpers;

use App\Helpers\UserHelper;
use App\Exercise_category;
use App\Exercise;
use App\Statistics;
use App\User;
use App\Message;
use App\Program;
use DB;
use Validator;
use URL;
use Carbon\Carbon;

/**
 * 
 * Handles the messaging.
 * 
 */
class MessageHelper
{
    
    public static function makeMessageBoard($user)
    {
        $messages = MessageHelper::sortMessages($user->messages, $user->id, $user->id);
        
        $board = view('partials.message_board')->with('messages', $messages)->with('owner', $user->id);
        
        return $board;
    }
    
    public static function sortMessages($messages, $owner_id, $loggedUser_id)
    {
        $writers = array();
        
        foreach($messages as $message)
        {
            if(!array_key_exists($message->writer_id, $writers))
            {
                $user = User::find($message->writer_id);
                $fetched_avatar = $user->settings->avatar;
                $avatar = empty($fetched_avatar) || is_null($fetched_avatar) || $fetched_avatar === "" ? 'placeholder.png' : $fetched_avatar;
                $writers[$message->writer_id] = array("id" => $user->id, "username" => $user->username, "avatar" => empty($avatar) || is_null($avatar) ? 'placeholder.png' : $avatar);
            }
            $writer = $writers[$message->writer_id];
            $message['username'] = htmlspecialchars($writer["username"], ENT_QUOTES, 'UTF-8');
            $message['avatar'] = URL::asset('storage/avatars/'.htmlspecialchars($writer["avatar"], ENT_QUOTES, 'UTF-8'));
            $message['url'] = url('profile/'.$writer["id"]);
            $message['isOwner'] = ($message->writer_id === $loggedUser_id || $owner_id === $loggedUser_id) ? 1 : 0;
        }
        
        return $messages;
    }
    
    public static function newMessage($user_wall_id, $writer, $message)
    {
        $validator = MessageHelper::messageValidator(array("writer_id" => $writer->id, "user_id" => $user_wall_id, "message" => $message), $writer);
        
        if ($validator->fails()) {
            return json_encode(array("error" => $validator->errors()->all()));
        }
        $message_encoded = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');
        try
        {
            $msg = new Message();
            $msg->writer_id = $writer->id;
            $msg->user_id = $user_wall_id;
            $msg->message = $message_encoded;
            $msg->save();
            MessageHelper::removeExtraMessages($user_wall_id);
        }
        catch(Exception $e)
        {
            return json_encode(array("error" => $e));
        }
        
        UserHelper::checkAndSetSessionPic();
        
        $returned = array(
                "id" => $msg->id,
                "username" => $writer->username,
                "url" => url('profile/'.$writer->id),
                "avatar" => URL::asset('storage/avatars/'.session('profilepic')),
                "created_at" => Carbon::now()->toDateTimeString(),
                "message" => $message_encoded,
                "isOwner" => 1
            );
        return json_encode($returned);
    }
    
    private static function removeExtraMessages($user_id)
    {
        $user = User::find($user_id);
        $messages = $user->messages;
        while($messages->count() > 5)
        {
            $messages[0]->delete();
            $messages->shift();
        }
    }
    protected static function messageValidator(array $data, $writer)
    {
        $friend_array = $writer->friends;
        $friends = '';
        if($friend_array->count() > 0)
        {
            $friends = ','.implode(",", array_column($friend_array->toArray(), 'id'));
        }
        return Validator::make($data, [
            'writer_id' => 'required|numeric|exists:hus_user,id',
            'user_id' => 'required|numeric|exists:hus_user,id|in:'.$data["writer_id"].$friends,
            'message' => 'required|max:255|min:1'
        ]);
    }
}
