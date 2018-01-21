<?php


namespace Kaduev13\EventLoopProfiler\Event;

use Kaduev13\EventLoopProfiler\Proxy\TimerProxy;

class TimerListenerEvent extends TimerEvent
{
    /**
     * @var TimerProxy
     */
    protected $timer;

    /**
     * @var ListenerEvent
     */
    protected $event;

    public static function getName()
    {
        return 'timer.listener';
    }

    /**
     * @param TimerProxy $timer
     * @param ListenerEvent $event
     */
    public function __construct(TimerProxy $timer, ListenerEvent $event)
    {
        parent::__construct($timer);
        $this->timer = $timer;
        $this->event = $event;
    }

    /**
     * @return TimerProxy
     */
    public function getTimer(): TimerProxy
    {
        return $this->timer;
    }

    /**
     * @return ListenerEvent
     */
    public function getEvent(): ListenerEvent
    {
        return $this->event;
    }
}
