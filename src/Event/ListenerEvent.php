<?php

namespace Kaduev13\EventLoopProfiler\Event;

use Kaduev13\EventLoopProfiler\Proxy\ListenerProxy;

abstract class ListenerEvent extends Event
{
    /**
     * @var ListenerProxy
     */
    protected $listener;

    public function __construct(ListenerProxy $listener)
    {
        parent::__construct();
        $this->listener = $listener;
    }

    /**
     * @return ListenerProxy
     */
    public function getListener(): ListenerProxy
    {
        return $this->listener;
    }
}
