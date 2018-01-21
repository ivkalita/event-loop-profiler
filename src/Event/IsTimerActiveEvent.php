<?php

namespace Kaduev13\EventLoopProfiler\Event;

class IsTimerActiveEvent extends Event
{
    protected $timer;

    public function __construct($timer)
    {
        parent::__construct();
        $this->timer = $timer;
    }
}
