<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Auth;
use DB;

class Survey extends Model
{
    /**
     *
     * The table where this model belongs to
     * 
     * @var string 
     */
    protected $table = 'hus_survey';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_fk', 'vitality_state'
    ];
    
    protected $casts = [
        'user_fk' => 'integer',
		'vitality_state' => 'integer'
    ];
	
	public static function canFill() {
		$created = DB::table('hus_survey')->where('user_fk', Auth::id())->orderBy('created_at')->first();
		if (!$created) return true;
		$created = new Carbon($created->created_at);
		$now = Carbon::now();
		return ($created->diff($now)->days > 6);
	}
}
