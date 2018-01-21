<?php


namespace Kaduev13\EventLoopProfiler\Event;

class ListenerStartedEvent extends ListenerEvent
{
    public static function getName()
    {
        return 'listener.started';
    }
}
