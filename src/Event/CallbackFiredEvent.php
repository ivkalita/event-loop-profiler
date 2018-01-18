<?php
/**
 * @author Ivan Kalita
 */

namespace Kaduev13\EventLoopProfiler\Event;

class CallbackFiredEvent extends Event
{
    /**
     * @var callable
     */
    protected $callable;

    /**
     * @var Event
     */
    protected $parentEvent;

    /**
     * @param callable $callable
     * @param Event $parentEvent
     */
    public function __construct($callable, Event $parentEvent)
    {
        parent::__construct();
        $this->callable = $callable;
        $this->parentEvent = $parentEvent;
    }

    /**
     * @return callable
     */
    public function getCallable()
    {
        return $this->callable;
    }
}
