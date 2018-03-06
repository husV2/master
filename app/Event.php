<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;
    /**
     *
     * The table where this model belongs to
     * 
     * @var string 
     */
    protected $table = 'hus_ex_event';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_fk', 'ex_fk', 'ex_prog_fk', 'start_date'
    ];
    protected $casts = [
        'isActive' => 'boolean'
    ];
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'start_date'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'completed', 'complete_date',
        
    ];
    
    /**
     * 
     * Returns the user who owns this event.
     * 
     * @return User
     * @see User
     */    
    public function user()
    {
        return $this->belongsTo('App\User', 'user_fk');
    }
     /**
     * 
     * Returns the exercise associated with this event.
     * 
     * @return Exercise
     * @see Exercise
     */    
    public function exercise()
    {
        return $this->belongsTo('App\Exercise', 'ex_fk');
    }
    /**
     * 
     * Returns the program in which this event belongs to.
     * 
     * @return Program
     * @see Program
     */ 
    public function program()
    {
        return $this->belongsTo('App\Program', 'ex_prog_fk');
    }
    

}