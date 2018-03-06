<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Helpers\StatisticsHelper;
use App\Helpers\UserHelper;
use App\Helpers\GroupHelper;
use App\Helpers\MessageHelper;
use Auth;
use App\User;
use App\Program;
use App\Events\VisitProfile;

/*
 * 
 * Handles the display of visible pages except for login, register and password reset.
 * 
 */
class MainController extends Controller
{
    private $statisticsRepo;
    /**
     * Create a new controller instance.
     * Authenticates the user before any page, that this controller controls, is displayed
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth_with_pic','verify']);
        $this->statisticsRepo = new StatisticsHelper();
    }

    /**
     * Displays the "You've successfully logged in" - page.
     * Checks whether new events need to be generated for user
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $user = Auth::user();
        $modal = view('partials.basicModal')->with('id', 'eventModal');
		$canFill = true;

        $content = view('partials.settings_modal_content')
                ->with('userProgram', $user->settings->ex_program_fk)
                ->with('settings', Userhelper::setCalendarSettingsList($user))
                ->with('programs', Program::all());
        $settings_modal = view('partials.basicModal')
                ->with('id', 'settings_modal')
                ->with('content', $content)
                ->with('title', trans('main.settings'));
        $view = view('home')
                ->with('modal', $modal)
                ->with('settings_modal', $settings_modal);
        
        return $view;
    }
    /**
     * Show the user profile page.
     * 
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        $user = Auth::user();
        $friends = $user->friends;
        $ret_friends = UserHelper::setFriendList($friends);
        $ret_settings = UserHelper::setSettingsList($user);
        $users = UserHelper::setUserListWithoutFriends($user);
        //$buddyHP = StatisticsHelper::buddyHP($user);
        $streaks = StatisticsHelper::loginStreak($user);
        
        $grid = view('partials.searchGrid')->with('items',$users);
        $modal = view('partials.basicModal')->with('id', 'friendModal')->with('content', trans('main.friend_request_sent'))->with('no_header', true)->with('no_footer', true);
        $accomplishment = view('chart.accomplishments')
                ->with('title', '')
                ->with('personal_stats', $this->statisticsRepo->personalStats($user));
        //$gamestats = view('chart.myLevel')->with('stats', $game);
        $achievementImgs = UserHelper::getAchievementImgs($user);
        $settings_tab = view('partials.profile_settings_tab')->with('settings', UserHelper::setSettingsListForForm($user));
        
        $view = view('profile')
                ->with('friends', $ret_friends)
                ->with('modal', $modal)
                ->with('settings', $ret_settings)
                ->with('login_streak', $streaks)
                ->with('chart', $accomplishment)
                ->with('achievements', $achievementImgs)
                ->with('messageBoard', MessageHelper::makeMessageBoard($user))
                ->with('settings_tab', $settings_tab)
                ->with('password_tab', view('partials.profile_password_tab'))
                ->with('grid', $grid);
        
        return $view;
    }
    
    public function guestProfile($id)
    {
        if($id == Auth::user()->id)
        {
            return redirect('/profile');
        }
        $user = User::find($id);
        
        if(empty($user)){ abort(404); }
        $achievement = event(new VisitProfile());
        $friends = $user->friends;
        $ret_friends = UserHelper::setFriendList($friends);
        $fields = ["event_interval", "ex_program", "workhours"];
        $ret_settings = UserHelper::addPrivacyToList(UserHelper::setSettingsList($user), $fields);
        $streaks = StatisticsHelper::loginStreak($user);
        //$game = StatisticsHelper::buddyHP($user);
        $achievementImgs = UserHelper::getAchievementImgs($user);
        
        $accomplishment = view('chart.accomplishments')
                ->with('title', '')
                ->with('personal_stats', $this->statisticsRepo->personalStats($user));
        //$gamestats = view('chart.myLevel')->with('stats', $game);
        
        $view = view('profile')
                ->with('guest', true)
                ->with('login_streak', $streaks)
                ->with('friends', $ret_friends)
                ->with('settings', $ret_settings)
                ->with('achievements', $achievementImgs)
                ->with('messageBoard', MessageHelper::makeMessageBoard($user)->with('guest', $id))
                ->with('chart', $accomplishment);
        return empty($achievement) ? $view : $view->with('achievement', $achievement);
    }
    
//    public function editProfile()
//    {
//        $user = Auth::user();
//        $ret_settings = UserHelper::setSettingsListForForm($user);
//
//        $view = view('profile_edit')
//                ->with('settings', $ret_settings);
//        
//        return $view;
//    }
    
    /*
     * Displays the statistics page with different charts.
     * 
     * @return \Illuminate\Http\Response
     */
    public function charts()
    {
        $accomplishment = view('chart.accomplishments')
                ->with('title', trans('main.your_accomplishments'))
                ->with('personal_stats', $this->statisticsRepo->personalStats(Auth::user()));
        $upper_department = view('chart.department_ranking')
                ->with('stats', $this->statisticsRepo->departmentRanking(1))
                ->with('id', 1);
        $department = view('chart.department_ranking')
                ->with('stats', $this->statisticsRepo->departmentRanking(2))
                ->with('id', 2);
        $friend_activity = view('chart.friend_activity')
                ->with('friend_stats', $this->statisticsRepo->friend_activity())
                ->with('title', trans('main.activity'));
        
        $weekly_activity = view('chart.my_weekly_activity')
                ->with('weekly_stats', $this->statisticsRepo->weekly_activity())
                ->with('title', trans('main.daily_activity'))
                ->with('text_completed', trans('main.completed'))
                ->with('text_of_all', trans('main.of_all_daily_events'));
       
        
        return view('statistics')
                ->with('accomplishment', $accomplishment)
                ->with('department', $department)
                ->with('upper_department', $upper_department)
                ->with('friend_activity', $friend_activity)
                ->with('weekly_activity', $weekly_activity)
                ->with('width', 600)
                ->with('height', 600);
    }
    
}
