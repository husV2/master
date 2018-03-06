<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Auth;

class Settings extends Model
{
    /**
     *
     * The table where this model belongs to
     * 
     * @var string 
     */
    protected $table = 'hus_user_settings';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_fk', 'avatar', 'ex_program_fk', 'event_interval', 'motto', 'workhours', 'theme', 'generateRandom'
    ];
    /**
     *  Attributes for mass update
     * @var array
     */
    public $fields = [
        'ex_program_fk', 'event_interval', 'motto', 'workhours', 'generateRandom'
    ];
    protected $casts = [
        'event_interval' => 'integer',
        'motto' => 'string',
        'workhours' => 'integer',
        'generateRandom' => 'boolean'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
    
    public function user()
    {
        return $this->belongsTo('App\User', 'user_fk');
    }
    public function event_interval()
    {
        return $this->event_interval;
    }
    public static function createDefault()
    {
        $settings = new Settings();
        $settings->user_fk = Auth::user()->id;
        $settings->save();
        
        return $settings;
        
    }
    
//    public function getProgram()
//    {
//        return $this->belongsTo('App\Program', 'ex_program_fk');
//    }
    
}
