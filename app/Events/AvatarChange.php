<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;

class AvatarChange extends Event
{
    use SerializesModels;
    
    public $avatar;
    
    /* ID of this achievement in the hus_achievement table */
    public $id = 1;

    /**
     * Create a new event instance.
     *
     * @param $avatar
     * @return void
     */
    public function __construct($avatar)
    {
        $this->avatar = $avatar;
    }

}
