<?php


namespace Kaduev13\EventLoopProfiler\Event;


class RunEvent extends Event
{
    public static function getName()
    {
        return 'run';
    }
}