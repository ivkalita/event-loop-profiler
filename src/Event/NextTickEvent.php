<?php
/**
 * @author Ivan Kalita
 */

namespace Kaduev13\EventLoopProfiler\Event;

class NextTickEvent extends Event
{
    protected $listener;

    public function __construct($listener)
    {
        $this->listener = $listener;
    }
}
