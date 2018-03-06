<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Settings;
use App\Buddy;
use Carbon\Carbon;
use Validator;
use App\Helpers\GroupHelper;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Input;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';
    protected $username = 'username';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    { 
        for($i=1; $i<=5; $i++)
        {
            if(!empty($data['group'.$i]) && $data['group'.$i] != 'none')
            {
                $lastpart = explode("_" , $data['group'.$i]);
                if(count($lastpart) > 1)
                {
                    $data["g".$i] = $lastpart[1];
                }
            }
        }
        return Validator::make($data, [
            'username' => 'required|max:60|min:1|unique:hus_user',
            'firstname' => 'max:255',
            'lastname' => 'max:255',
//            'email' => 'required|email|max:255|regex:[a-zA-Z0-9\.]*@(hus.fi)|unique:hus_user',
            'email' => 'required|email|max:255|unique:hus_user', /* Testing purposes */
            'password' => 'required|confirmed|min:6',
            'group1' => 'required',
            'group2' => 'required',
            'profilepic' => 'image|max:10000',
            'workhour' => 'numeric',
            'motto' => 'max:255',
            'g1' => 'required|numeric|exists:hus_group_1,id',
            'g2' => 'required|numeric|exists:hus_group_2,id',
            'g3' => 'numeric|exists:hus_group_3,id',
            'g4' => 'numeric|exists:hus_group_4,id',
            'g5' => 'numeric|exists:hus_group_5,id',
              
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        $confirmation_code = uniqid (str_random(30));
        if(!RegistrationController::sendVerificationMail($confirmation_code, Input::get('email'), Input::get('username')))
        {
            return null;
        }
        $user = User::create([
            'username' => htmlspecialchars($data['username'], ENT_QUOTES, 'UTF-8'),
//            'firstname' => $data['firstname'],
//            'lastname' => $data['lastname'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'hus_group_1_id' => explode("_" , $data['group1'])[1],
            'hus_group_2_id' => explode("_" , $data['group2'])[1],
            'hus_group_3_id' => !isset($data['group3']) || empty($data['group3']) || $data['group3'] === "none" ? NULL : explode("_" , $data['group3'])[1],
            'hus_group_4_id' => !isset($data['group4']) || empty($data['group4']) || $data['group4'] === "none" ? NULL : explode("_" , $data['group4'])[1],
            'hus_group_5_id' => !isset($data['group5']) || empty($data['group5']) || $data['group5'] === "none" ? NULL : explode("_" , $data['group5'])[1],
            'confirmation_code' => $confirmation_code
        ]);
        
        $this->createSettings($user, $data);
        $this->createBuddy($user);

        return $user;
    }
    
    public function showLoginForm()
    {
        $main_groups = GroupHelper::all(1);
        $data = array();
        
        foreach($main_groups as $group)
        {
            $data[$group->id] = $group->name;
        }

//        if (property_exists($this, 'registerView')) {
//            return view($this->registerView);
//        }
        $modal = view('auth.login_modal')->with('data', $data);
        return view('auth.login')
                ->with('modal', $modal);
        
    }
    
    public function showRegistrationForm()
    {
        $main_groups = GroupHelper::all(1);
        $data = array();
        
        foreach($main_groups as $group)
        {
            $data[$group->id] = $group->name;
        }

//        if (property_exists($this, 'registerView')) {
//            return view($this->registerView);
//        }

        return view('auth.register')->with('data', $data);
    }
    
    protected function createBuddy($user)
    {
        Buddy::create([
            'owner_id' => $user->id,
            'login_streak' => 1,
            'ex_streak_date' => Carbon::today()
        ]);
    }
    
    protected function createSettings($user, $data)
    {
        Settings::create([
            'user_fk' => $user->id,
            'workhours' => htmlspecialchars($data['workhour'], ENT_QUOTES, 'UTF-8'),
            'avatar' => NULL,
            'theme' => isset($data['theme']) ? htmlspecialchars($data['theme'], ENT_QUOTES, 'UTF-8') : 'theme_green',
            'motto' => htmlspecialchars($data['motto'] , ENT_QUOTES, 'UTF-8')   
        ]);
    }
}
