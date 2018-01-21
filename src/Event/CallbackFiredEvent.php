<?php

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
    protected $context;

    /**
     * @param callable $callable
     */
    public function __construct($callable)
    {
        parent::__construct();
        $this->callable = $callable;
    }

    /**
     * @return callable
     */
    public function getCallable()
    {
        return $this->callable;
    }
}
