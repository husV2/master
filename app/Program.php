<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Program extends Model
{
    /**
     *
     * The table where this model belongs to
     * 
     * @var string 
     */
    protected $table = 'hus_ex_program';
	protected $exTable = 'hus_ex_program_exercise';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'isActive'
    ];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'isActive'
    ];
    
    protected $casts = [
        'isActive' => 'boolean'
    ];
	
    private function removeAllExcercises()
    {
         DB::table($this->exTable)->where('ex_prog_fk', '=', $this->id)->delete();
    }
    
    public function exercises()
    {
        return $this->belongsToMany('App\Exercise', 'hus_ex_program_exercise', 'ex_prog_fk', 'exercise_fk')
            ->withPivot('id','day', 'order_num')
            ->orderBy('order_num');
    }
    public function daily($day)
    {
        return $this->belongsToMany('App\Exercise', 'hus_ex_program_exercise', 'ex_prog_fk', 'exercise_fk')
            ->withPivot('id','day', 'order_num')
            ->where('day','=', $day)
            ->orderBy('order_num');
    }
    public function settings()
    {
        return $this->hasMany('App\Settings', 'ex_program_fk');
    }
	
    public function setNewExercises($exArray) 
    {
        $this->removeAllExcercises();
        $day = 1;
        foreach ($exArray as $dayExcercises) {
            $order_num = 0;
            foreach ($dayExcercises as $exercise) {
                    DB::table($this->exTable)->insert([
                            'ex_prog_fk' => $this->id,
                            'exercise_fk' => $exercise,
                            'day' =>$day,
                            'order_num' => $order_num
                    ]);
                    $order_num++;
            }
            /* There are only 7 days so the maximum number for a day is 6. Sunday is 0 so that's why we need this check */
            $day = (($day === 6) ? 0 : $day + 1);
        }
    }
}
