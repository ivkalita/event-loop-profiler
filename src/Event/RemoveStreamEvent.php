<?php

namespace Kaduev13\EventLoopProfiler\Event;

class RemoveStreamEvent extends Event
{
    protected $stream;
    protected $listener;

    public function __construct($stream)
    {
        parent::__construct();
        $this->stream = $stream;
    }
}
