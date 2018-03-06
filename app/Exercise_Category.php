<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exercise_Category extends Model
{
    /**
     *
     * The table where this model belongs to
     * 
     * @var string 
     */
    protected $table = 'hus_ex_category';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'type',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
    
    /**
     * 
     * Returns all the exercises that belong to this category.
     * 
     * @return Array(Exercise)
     * @see Category
     */    
    public function exercises()
    {
        return $this->hasMany('App\Exercise', 'ex_category_fk');
    }
}