<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Auth;
use DB;
use Carbon\Carbon;

class SecurityLog extends Model
{
    protected $table = "hus_password_security";
    protected $primaryKeys = array("user_id", "token");
    
    protected $daysToExpire = 7;
    
    /**
    *  Attributes for mass update
    * @var array
    */
    public $fields = [
        'user_id', 'ip', 'old', 'token'
    ];
    protected $casts = [
        'user_id' => 'integer',
        'ip' => 'string',
        'old' => 'integer',
        'token' => 'string'
    ];
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
    public static function findWithToken($token)
    {
        return SecurityLog::where('token', '=', $token)
                ->first();
    }
    public function clear()
    {
        return DB::table($this->table)->where($this->primaryKeys[0], '=', $this->user_id)->delete();
    }
    public function clearExpired()
    {
        $addweek = $this->created_at->copy()->startOfDay()->addDays($this->daysToExpire);
        if($addweek->lte(Carbon::today()))
        {
            return DB::table($this->table)
                    ->where($this->primaryKeys[0], '=', $this->user_id)
                    ->where($this->primaryKeys[1], '=', $this->token)
                    ->delete();
        }
        return 0;
    }
}