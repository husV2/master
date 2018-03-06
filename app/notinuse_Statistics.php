<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Statistics extends Model
{
     /**
     *
     * The table where this model belongs to
     * 
     * @var string 
     */ 
    protected $level;
    protected $group;
    protected $class;
    public $primaryKey = 'group_fk';
    
    protected $fillable = [
        'completes', 'skips', 'timeSpent', 'group_fk'
    ];
    
    protected $casts = [
        'completes' => 'integer',
        'skips' => 'integer',
        'timeSpent' => 'bigInteger'
    ];
    
    public function __construct($level = 1)
    {
        $this->group = 'group_fk';
        $this->level = $level;
        
        parent::__construct();
    }
    
    /**
     * Gets the results sorted by the ratio of completed exercises to all exercises (skipped + completed, not ignored)
     * 
     **/
    public function skipCompleteRelation()
    {
        if(!Helpers\GroupHelper::exists($this->level))
        {
            return false;
        }
        return DB::table('hus_statistics_'.$this->level)
                ->select(DB::raw('*, completes/(completes+skips) as "all"'))
                ->orderBy('all', 'desc')
                ->orderBy('completes', 'desc')
                ->get();
    }
}
