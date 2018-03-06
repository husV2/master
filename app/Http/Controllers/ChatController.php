<?php
namespace App\Http\Controllers;

use Validator;
use Redirect;
use App\Http\Requests;
use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Message;
use App\Helpers\MessageHelper;


class ChatController extends Controller
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
     * Send message to other user's wall
     */
    public function sendMessage(Request $req)
    {
        $writer = Auth::user();
        return MessageHelper::newMessage($req["user_wall"], $writer, $req["message"]);
    }
    
    /*
     * Get the id of the newest message
     */
    public function getMissedMessageIDs(Request $req)
    {
        $returned = array("status" => 0, "ids" => []);
        if(!isset($req["owner"]))
        {
            return json_encode($returned);
        }
        $user = User::find($req["owner"]);
        $messages = $user->messages()->whereNotIn("id", $req["ids"])->select('id')->get();
        $returned["status"] = 1;
        $returned["ids"] = $messages;
        
        return json_encode($returned);
    }
    
    /*
     * Update the chat window
     */
    public function updateChat(Request $req)
    {
        $returned = array("status" => 0, "content" => "");
        if(!isset($req["ids"]) || !isset($req["owner"]))
        {
            return json_encode($returned);
        }
        $user = User::find($req["owner"]);
        $messages = $user->messages()->whereIn('id', $req['ids'])->get();

        $returned["status"] = 1;
        $returned["content"] = MessageHelper::sortMessages($messages, $user->id, Auth::user()->id);
        
        return json_encode($returned);
    }
    
    /*
     * Remove message from the chat
     * 
     */
    public function removeMessage(Request $req)
    {
        $message = Message::find($req["id"]);
        $user = Auth::user();
        if(empty($message))
        {
            header('HTTP/1.1 400 Message not found');
            header('Content-Type: application/json; charset=UTF-8');
            die(json_encode(["error" => "Message with the supplied id could not be found!"]));
        }
        if($message->writer->id !== $user->id && $message->wall->id !== $user->id)
        {
            header('HTTP/1.1 401 Unauthorized');
            header('Content-Type: application/json; charset=UTF-8');
            die(json_encode(["error" => "You are not the owner of the message or the message board!"]));
        }
        
        $message->delete();
        return json_encode(["success" => "Message deleted successfully"]);
    }
}

