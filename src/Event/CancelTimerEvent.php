<?php
/**
 * @author Ivan Kalita
 */

namespace Kaduev13\EventLoopProfiler\Event;

use React\EventLoop\Timer\TimerInterface;

class CancelTimerEvent extends Event
{
    protected $timer;

    public function __construct(TimerInterface $timer)
    {
        $this->timer = $timer;
    }
}
