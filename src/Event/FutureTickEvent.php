<?php
/**
 * @author Ivan Kalita
 */

namespace Kaduev13\EventLoopProfiler\Event;

class FutureTickEvent extends Event
{
    protected $listener;

    public function __construct($listener)
    {
        $this->listener = $listener;
    }
}
