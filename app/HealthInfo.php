<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class HealthInfo extends Model
{
    /**
     *
     * The table where this model belongs to
     * 
     * @var string 
     */
    protected $table = 'hus_health_info';
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
		'message', 'is_active'
    ];
	
	public static function getActiveInfos() {
		return DB::table('hus_health_info')->where('is_active', 1)->get();
	}
}
