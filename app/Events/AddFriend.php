<?php

namespace App\Events;

use App\Events\Event;
use App\Achievement;
use Illuminate\Queue\SerializesModels;

class AddFriend extends Event
{
    use SerializesModels;
    
    public $friend;
    
    /* ID of this achievement in the hus_achievement table */
    public $id = 3;
    

    /**
     * Create a new event instance.
     *
     * @param $friend_id
     * @return void
     */
    public function __construct($friend_id)
    {
        $this->friend = $friend_id;
    }

}