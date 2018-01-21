<?php


namespace Kaduev13\EventLoopProfiler\Event;

use Kaduev13\EventLoopProfiler\Proxy\TimerProxy;

abstract class TimerEvent extends Event
{
    /**
     * @var TimerProxy
     */
    protected $timer;

    public function __construct(TimerProxy $timer)
    {
        parent::__construct();
        $this->timer = $timer;
    }

    /**
     * @return TimerProxy
     */
    public function getTimer(): TimerProxy
    {
        return $this->timer;
    }
}
