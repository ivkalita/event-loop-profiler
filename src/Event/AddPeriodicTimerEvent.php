<?php

namespace Kaduev13\EventLoopProfiler\Event;

class AddPeriodicTimerEvent extends Event
{
    protected $interval;
    protected $callback;

    public function __construct($interval, $callback)
    {
        parent::__construct();
        $this->interval = $interval;
        $this->callback = $callback;
    }
}
