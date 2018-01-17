<?php
/**
 * @author Ivan Kalita
 */

namespace Kaduev13\EventLoopProfiler\Event;

class RemoveStreamEvent extends Event
{
    protected $stream;
    protected $listener;

    public function __construct($stream)
    {
        $this->stream = $stream;
    }
}
