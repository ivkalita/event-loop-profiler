<?php


namespace Kaduev13\EventLoopProfiler\Proxy;

use Kaduev13\EventLoopProfiler\Event\ListenerCompletedEvent;
use Kaduev13\EventLoopProfiler\Event\ListenerFailedEvent;
use Kaduev13\EventLoopProfiler\Event\ListenerStartedEvent;

class ListenerProxy extends Proxy
{
    private $listener;

    public $wrapped;

    public function __construct($listener)
    {
        parent::__construct();
        $this->listener = $listener;
        $this->wrapped = function (...$arguments) {
            $this->__call(null, $arguments);
        };
    }

    public function __call($name, $arguments)
    {
        try {
            $this->dispatcher->dispatch(ListenerStartedEvent::getName(), new ListenerStartedEvent($this));
            $result = call_user_func_array($this->listener, $arguments);
            $this->dispatcher->dispatch(ListenerCompletedEvent::getName(), new ListenerCompletedEvent($this, $result));
            return $result;
        } catch (\Throwable $e) {
            $this->dispatcher->dispatch(ListenerFailedEvent::getName(), new ListenerFailedEvent($this, $e));
            throw $e;
        }
    }
}
