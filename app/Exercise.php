<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    /**
     *
     * The table where this model belongs to
     * 
     * @var string 
     */
    protected $table = 'hus_exercise';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'content_html', 'duration', 'count', 'ex_category_fk','name', 'description', 'image', 'video', 'audio'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
    
    protected $casts = [
        'id' => 'integer',
        'count' => 'integer',
        'duration' => 'double',
        'ex_category_fk' => 'integer',
        'description' => 'string',
        'video' => 'string',
        'audio' => 'string'
    ];
    
    /**
     * 
     * Returns the category where this exercise belongs to.
     * 
     * @return Category
     * @see Category
     */    
    public function category()
    {
        return $this->belongsTo('App\Exercise_category', 'ex_category_fk');
    }
    public function events()
    {
        return $this->hasMany('App\Event');
    }
    public function program()
    {
        return $this->belongsToMany('App\Program', 'ex_prog_fk')->withPivot('day', 'order_num')->orderBy('order_num');
    }
}