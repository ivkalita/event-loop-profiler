<?php
/**
 * @author Ivan Kalita
 */

namespace Kaduev13\EventLoopProfiler\Event;

class AddPeriodicTimerEvent extends Event
{
    protected $interval;
    protected $callback;

    public function __construct($interval, $callback)
    {
        $this->interval = $interval;
        $this->callback = $callback;
    }
}
