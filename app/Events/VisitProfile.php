<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;

class VisitProfile extends Event
{
    use SerializesModels;
    
    public $id = 4;

    /**
     * Create a new event instance.
     *
     * @param Settings $settings
     * @return void
     */
    public function __construct()
    {
    }

}