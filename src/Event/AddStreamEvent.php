<?php
/**
 * @author Ivan Kalita
 */

namespace Kaduev13\EventLoopProfiler\Event;

class AddStreamEvent extends Event
{
    protected $stream;
    protected $listener;

    public function __construct($stream, $listener)
    {
        $this->stream = $stream;
        $this->listener = $listener;
    }
}
