<?php

namespace Kaduev13\EventLoopProfiler\Event;

use React\EventLoop\Timer\TimerInterface;

class CancelTimerEvent extends Event
{
    protected $timer;

    public function __construct(TimerInterface $timer)
    {
        parent::__construct();
        $this->timer = $timer;
    }
}
