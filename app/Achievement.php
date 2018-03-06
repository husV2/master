<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use URL;
use DB;

class Achievement extends Model
{
    /**
     *
     * The table where this model belongs to
     * 
     * @var string 
     */
    protected $table = 'hus_achievement';
    
    protected $fillable = [
        'name', 'description', 'image', 'breakpoint', 'category_id'
    ];
    
    protected $casts = [
        'name' => 'string',
        'description' => 'string',
        'image' => 'string',
        'count' => 'integer',
        'category_id' => 'integer',
        'breakpoint' => 'integer'
    ];
    
    public function Image()
    {
        $img = $this->image;
        return empty($img) ? null : URL::asset('storage/achievements/'.$img);
    }
    public static function countables()
    {
        return DB::table('hus_achievement')->where('breakpoint','!=', null)->get();
    }
    public function isCountable()
    {
        return !empty($this->breakpoint); 
    }
    public function category()
    {
        return $this->belongsTo('App\Exercise_category', 'category_id');
    }
    
}
