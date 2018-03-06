<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    /**
     *
     * The table where this model belongs to
     * 
     * @var string 
     */
    protected $table = 'hus_user_messages';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'message', 'writer_id', 'user_id'
    ];
    
    protected $casts = [
        'message' => 'string',
        'writer_id' => 'integer',
        'user_id' => 'integer'
    ];
    
    public function wall()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
    public function writer()
    {
        return $this->belongsTo('App\User', 'writer_id');
    }

}
