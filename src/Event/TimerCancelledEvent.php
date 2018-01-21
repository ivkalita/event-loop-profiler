<?php


namespace Kaduev13\EventLoopProfiler\Event;

class TimerCancelledEvent extends TimerEvent
{
    public static function getName()
    {
        return 'timer.cancelled';
    }
}
