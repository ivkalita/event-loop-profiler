<?php


namespace Kaduev13\EventLoopProfiler\Event;

class TimerAddedEvent extends TimerEvent
{
    public static function getName()
    {
        return 'timer.added';
    }
}
