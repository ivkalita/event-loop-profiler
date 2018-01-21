<?php

namespace Kaduev13\EventLoopProfiler\Event;

class NextTickEvent extends Event
{
    protected $listener;

    public function __construct($listener)
    {
        parent::__construct();
        $this->listener = $listener;
    }
}
