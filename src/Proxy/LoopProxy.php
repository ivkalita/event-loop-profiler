<?php

namespace Kaduev13\EventLoopProfiler\Proxy;

use Kaduev13\EventLoopProfiler\Event\ListenerCompletedEvent;
use Kaduev13\EventLoopProfiler\Event\ListenerEvent;
use Kaduev13\EventLoopProfiler\Event\ListenerFailedEvent;
use Kaduev13\EventLoopProfiler\Event\ListenerStartedEvent;
use Kaduev13\EventLoopProfiler\Event\LoopCreatedEvent;
use Kaduev13\EventLoopProfiler\Event\RunEvent;
use Kaduev13\EventLoopProfiler\Event\TimerAddedEvent;
use Kaduev13\EventLoopProfiler\Event\TimerCancelledEvent;
use Kaduev13\EventLoopProfiler\Event\TimerListenerEvent;
use React\EventLoop\LoopInterface;
use React\EventLoop\Timer\TimerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class LoopProxy
 *
 * Records all the react-php loop instance activities.
 *
 * @method addReadStream($stream, callable $listener)
 * @method addWriteStream($stream, callable $listener)
 * @method removeReadStream($stream)
 * @method removeWriteStream($stream)
 * @method removeStream($stream)
 * @method isTimerActive(TimerInterface $timer)
 * @method nextTick(callable $listener)
 * @method futureTick(callable $listener)
 * @method tick()
 * @method stop()
 */
class LoopProxy
{
    /**
     * Real loop instance.
     *
     * @var LoopInterface
     */
    private $realLoop;

    /**
     * @var EventDispatcher
     */
    public $dispatcher;

    /**
     * @var array
     */
    public $timers;

    /**
     * LoopProxy constructor.
     *
     * @param LoopInterface $realLoop
     * @param EventSubscriberInterface[] $subscribers
     */
    public function __construct(LoopInterface $realLoop, array $subscribers)
    {
        $this->realLoop = $realLoop;
        $this->dispatcher = new EventDispatcher();
        $this->timers = [];
        foreach ($subscribers as $subscriber) {
            $this->dispatcher->addSubscriber($subscriber);
        }
        $this->dispatcher->dispatch(LoopCreatedEvent::getName(), new LoopCreatedEvent($realLoop));
    }

    /**
     * Main proxy method.
     *
     * @param $name
     * @param $arguments
     *
     * @return mixed|null
     */
    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->realLoop, $name], $arguments);
    }

    public function addTimer($interval, callable $callback)
    {
        return $this->proxyTimer($interval, $callback, false);
    }

    public function addPeriodicTimer($interval, callable $callback)
    {
        return $this->proxyTimer($interval, $callback, true);
    }

    private function proxyTimer($interval, callable $callback, bool $isPeriodic)
    {
        $listenerProxy = new ListenerProxy($callback);
        $callable = $isPeriodic ? 'addPeriodicTimer' : 'addTimer';
        $realTimer = call_user_func([$this->realLoop, $callable], $interval, $listenerProxy->wrapped);
        $timer = new TimerProxy($realTimer, $listenerProxy);

        $forwardEvent = function (ListenerEvent $event) use (&$timer) {
            $this->dispatcher->dispatch(TimerListenerEvent::getName(), new TimerListenerEvent($timer, $event));
        };

        $timer->dispatcher->addListener(ListenerStartedEvent::getName(), $forwardEvent);
        $timer->dispatcher->addListener(ListenerCompletedEvent::getName(), $forwardEvent);
        $timer->dispatcher->addListener(ListenerFailedEvent::getName(), $forwardEvent);

        $this->dispatcher->dispatch(TimerAddedEvent::getName(), new TimerAddedEvent($timer));

        return $timer;
    }

    public function cancelTimer(TimerInterface $timer)
    {
        if ($timer instanceof TimerProxy) {
            $this->dispatcher->dispatch(TimerCancelledEvent::getName(), new TimerCancelledEvent($timer));
            $timer = $timer->getTimer();
        }

        $this->realLoop->cancelTimer($timer);
    }

    public function run()
    {
        $this->dispatcher->dispatch(RunEvent::getName(), new RunEvent());
        $this->realLoop->run();
    }
}
