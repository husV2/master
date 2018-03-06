<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use DB;

class AdminNotification extends Model
{
    /**
     *
     * The table where this model belongs to
     * 
     * @var string 
     */
    protected $table = 'hus_admin_notification';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		'message', 'last_active_date', 'is_active'
    ];
	
	public static function getNotifications() {
		$now = Carbon::now()->toDateString();
		return DB::table('hus_admin_notification')->where('last_active_date', '>=', $now)->where('is_active', 1)->get();
	}
}
