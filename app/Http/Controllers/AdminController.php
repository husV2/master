<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Helpers\ExerciseHelper;
use Illuminate\Support\Facades\Validator;
use Redirect;

use App\Exercise;
use App\Exercise_Category;
use App\Program;
use App\AdminNotification;
use App\HealthInfo;
use App\Helpers\CacheHelper;
use Auth;

/**
 * 
 * Handles all the admin functionalities including the display of the admin page.
 * 
 */
class AdminController extends Controller
{
    protected $c;
    /*
     * Middleware to make sure that only verified admin accounts can access these functions.
     */
    public function __construct()
    {
        $this->middleware(['webadmin','verify']);
        $this->c = new CacheHelper();
    }
	
    public function index()
    {
        return view('admin.admin');
    }
    
    public function exerciseIndex()
    {
        return view('admin.exercises')->with('exercises', Exercise::all());
    }
	
	public function exProgramIndex()
    {
        return view('admin.ex_programs')->with('exprograms', Program::all());
    }
	
	public function healthinfoIndex()
	{
		return view('admin.healthinfo')->with('health_infos', HealthInfo::all());
	}
	
	public function notificationIndex()
	{
		return view('admin.notification')->with('notifications', AdminNotification::all()->sortBy('last_active_date'));
	}
	
    public function exercise($id = 0)
    {	
        $categories = Exercise_Category::all();	
        $ex = Exercise::find( $id );
        $view = view('admin.edit_exercise')->with('categories', $categories);

        if($ex !== null)
        {
            $view = $view->with('ex', $ex);
        }

        return $view;
    }
	
    public function ex_program($id = 0)
    {
        $categories = Exercise_Category::all();
        $exprogram = Program::find($id);
        $colors = array();

        foreach ($categories as $cat) {
                $colors[$cat->id] = $cat->color;
        }

        $view = view('admin.edit_ex_program')
        ->with('exercises',  Exercise::orderBy('ex_category_fk')->get())
        ->with('categories',  $categories)
                        ->with('colors', $colors);

        if($exprogram !== null)
        {
                $view = $view->with('exprogram', $exprogram)
                                          ->with('program_exercises', $exprogram->exercises()->get());
        }
		
        return $view;
    }
	
	public function healthinfo($id=0) {
		$info = HealthInfo::find($id);
		$view = view('admin.edit_healthinfo');
		if($info !== null) $view = $view->with('healthinfo', $info);
		return $view;
	}
	
    public function notification($id=0) {
		$notif = AdminNotification::find($id);
		$view = view('admin.edit_notification');
		if ($notif !== null) $view = $view->with('notification', $notif);
		return $view;
	}
	
    public function statistics()
    {
        $view = view('admin.statistics');
        $level = 1;
        $levels = array();
        while(\App\Helpers\GroupHelper::exists($level)){ array_push($levels, $level++); }
        $view->exerciseCounts = $this->c->getCompletesOrSkipped($levels, true);
        return $view;
    }
	
	public function storeNotification(Request $request) 
	{
		$encoded = $this->encodeArray($request->all());
		$notification = ($request["id"] === "-1") ? new AdminNotification() : AdminNotification::find($encoded["id"]);
		$notification->message = $encoded["message"];
		$notification->last_active_date = $encoded["last_active_date"];
		$notification->is_active = isset($encoded['is_active']) ? 1 : 0;
		$notification->save();
		return redirect('/admin/notification');
	}
	
	public function storeHealthinfo(Request $request) 
	{
		$encoded = $this->encodeArray($request->all());
		$healthinfo = ($request["id"] === "-1") ? new HealthInfo() : HealthInfo::find($encoded["id"]);
		$healthinfo->message = $encoded["message"];
		$healthinfo->is_active = isset($encoded['is_active']) ? 1 : 0;
		$healthinfo->save();
		return redirect('/admin/healthinfo');
	}
	
    public function storeExerciseProgram(Request $request)
    {
        $encoded = $this->encodeArray($request->all());
		
        $program = (($request["id"] === "-1") ? new Program() : Program::find($encoded["id"]));
        $program->name = $encoded['name'];
        $program->isActive = (isset($encoded['isActive']) ? 1 : 0);
        $program->save();
        $program->setNewExercises(json_decode($encoded['exprogram']));
        $program->save();
        return redirect('/admin/exprogram'); 
    }
    
    public function storeExercise(Request $request)
    {
        $validator = Validator::make($request->all(), [
                'name' => 'required|max:60|unique:hus_exercise,name,'.$request["id"].',id',
                'description' => 'max:255',
                'duration' => 'required|numeric|between:0.1,30',
                'count' => 'numeric|between:1,10',
                'category' => 'required|not_in:0',
                'text' => 'required|max:2000',
                'video' => 'sometimes|active_url',
                'image' => 'sometimes|mimes:jpeg,jpg,png,gif,bmp | max:1000'
        ]);
        
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
        $encoded = $this->encodeArray($request->all());
        if($request["id"] === "-1")
        {
            $exercise = new Exercise();
        }
        else
        {
            $exercise = Exercise::find($encoded["id"]);
        }
        $imgname = "";
        $audioname = "";
        $mime = "";
        if($request->hasFile('image'))
        {
            $imgname = str_random(30) . '.' . $request->file('image')->getClientOriginalExtension();
            $request->file('image')->move(
                  public_path().'/storage/exercises/images', $imgname
            );
            $exercise->image = $imgname;
        }
        if($request->hasFile('audio'))
        {
            $mime = $request->file('audio')->getMimeType();
            $audioname = str_random(30) . '.' . $request->file('audio')->getClientOriginalExtension();
            $request->file('audio')->move(
                  public_path().'/storage/exercises/audios', $audioname
            );
            $exercise->audio = $audioname;
        }
        
        $exercise->name = $encoded["name"];
        $exercise->description = $encoded["description"];
        $exercise->count = $encoded["count"];
        $exercise->duration = $encoded["duration"];
        $exercise->ex_category_fk = $encoded["category"];
        $exercise->text = $encoded["text"];
        $exercise->video = $encoded["video"];
        $exercise->content_html = ExerciseHelper::createContentHtml($encoded["text"], $encoded["video"], array( "file" => $audioname, "type" => $mime));
        $exercise->save();
        
        return redirect('/admin/exercise');
    }
    
    
    /**
     * Helper function to apply htmlspecialchars to all items in an array.
     * NEVER TRUST THE CLIENT
     */
    private function encodeArray($array)
    {
        $returned = array();
        foreach($array as $key => $item)
        {
            $returned[$key] = is_object($item) ? $item : htmlspecialchars($item, ENT_QUOTES, 'UTF-8');
        }
        return $returned;
    }
    
}
