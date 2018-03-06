<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Mail;
use Illuminate\Support\Facades\Input;
use App\User;
/*
 * 
 * Handles everything regarding the newly registered user verification.
 * 
 */
class RegistrationController extends Controller
{   
    /*
     * Sends the verification code via email to the newly registered user.
     * 
     * @return boolean
     */
    public static function sendVerificationMail($confirmation_code, $email, $username)
    {
        $data = ['confirmation_code' => $confirmation_code, 'email' => $email, 'username' => $username];
        
        Mail::send('auth.emails.verification', $data, function($message) use ($data) {
            $subject = trans('auth.verification_email_title');
            //$message->from('noreply@husminitreeni.fi', 'HUS minitreenirobotti');
            $message->to($data["email"], $data["username"])
                ->subject($subject);
        });
        if(count(Mail::failures()) > 0){
            return false;
        }
        
        return true;
    }
    /*
     * Attempts to verify the user with the code in the last part of the url.
     * 
     * @return \Illuminate\Http\Response
     */
    public function verify($confirmation_code)
    {
        /* If the confirmation code is somehow missing */
        if(!$confirmation_code)
        {
            return $this->showVerificationPage( trans('auth.verification_invalid'), TRUE );
        }
        
        $user = User::withConfirmationCode($confirmation_code);
        
        
        /* If there's no user with a given code */
        if(!$user)
        {
            return $this->showVerificationPage( trans('auth.verification_invalid'), TRUE );
        }
        
        $user->confirmed = 1;
        $user->confirmation_code = null;
        $user->save();
        
        return $this->showVerificationPage(trans('auth.verification_complete'),TRUE);
    }
    
    /*
     * Generates a verification code for the user.
     * 
     * @return \Illuminate\Http\Response
     */ 
    public function newVerificationCode()
    {
        $user = \Auth::user();
        
        if(empty($user))
        {
            return redirect('login');
        }
        
        $confirmation_code = uniqid (str_random(30));
        
        $user->addConfirmationCode($confirmation_code);
        
        if(!$this::sendVerificationMail($confirmation_code, $user->email, $user->username))
        {
            return $this->showVerificationPage(trans('auth.verification_not_done'));
        }
        
        return $this->showVerificationPage(trans('auth.verification_sent'),TRUE);
    }
    
    /*
     * Shows the email verification page. Takes in two variables, msg and mailSent.
     * If no message is set, the page will show the verification as incomplete.
     * If mailSent is true, meaning that verification mail was just sent, the appropriate text will be displayed.
     * 
     * @return \Illuminate\Http\Response
     */
    public function showVerificationPage($msg = NULL, $mailSent = FALSE)
    {
        if(empty($msg))
        {
            $msg = trans('auth.verification_not_done');
        }
        
        return \View::make('auth.verify')->with('data', ["msg" => $msg, "mailSent" => $mailSent]);
    }
}
