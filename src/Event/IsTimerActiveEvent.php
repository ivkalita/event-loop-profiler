<?php

namespace Kaduev13\EventLoopProfiler\Event;

use React\EventLoop\Timer\TimerInterface;

class IsTimerActiveEvent extends Event
{
    protected $timer;

    public function __construct(TimerInterface $timer)
    {
        parent::__construct();
        $this->timer = $timer;
    }
}
