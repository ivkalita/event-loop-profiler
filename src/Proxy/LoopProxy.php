<?php

namespace Kaduev13\EventLoopProfiler\Proxy;

use Kaduev13\EventLoopProfiler\Event\AddPeriodicTimerEvent;
use Kaduev13\EventLoopProfiler\Event\AddReadStreamEvent;
use Kaduev13\EventLoopProfiler\Event\AddTimerEvent;
use Kaduev13\EventLoopProfiler\Event\AddWriteStreamEvent;
use Kaduev13\EventLoopProfiler\Event\CallbackFiredEvent;
use Kaduev13\EventLoopProfiler\Event\CancelTimerEvent;
use Kaduev13\EventLoopProfiler\Event\Event;
use Kaduev13\EventLoopProfiler\Event\FutureTickEvent;
use Kaduev13\EventLoopProfiler\Event\IsTimerActiveEvent;
use Kaduev13\EventLoopProfiler\Event\NextTickEvent;
use Kaduev13\EventLoopProfiler\Event\RemoveReadStreamEvent;
use Kaduev13\EventLoopProfiler\Event\RemoveStreamEvent;
use Kaduev13\EventLoopProfiler\Event\RemoveWriteStreamEvent;
use Kaduev13\EventLoopProfiler\Event\RunEvent;
use Kaduev13\EventLoopProfiler\Event\StopEvent;
use Kaduev13\EventLoopProfiler\Event\TickEvent;
use React\EventLoop\LoopInterface;

/**
 * Class LoopProxy
 * @package Kaduev13\EventLoopProfiler\Proxy
 *
 * @method run()
 * @method addTimer($interval, callable $listener)
 * @method addReadStream($stream, callable $listener)
 * @method addWriteStream($stream, callable $listener)
 * @method removeReadStream($stream)
 * @method removeWriteStream($stream)
 * @method removeStream($stream)
 * @method addPeriodicTimer($interval, callable $listener)
 * @method cancelTimer($timer)
 * @method isTimerActive($timer)
 * @method nextTick(callable $listener)
 * @method futureTick(callable $listener)
 * @method tick()
 * @method stop()
 */
class LoopProxy
{
    const PROXY_METHODS = [
        'addReadStream' => AddReadStreamEvent::class,
        'addWriteStream' => AddWriteStreamEvent::class,
        'removeReadStream' => RemoveReadStreamEvent::class,
        'removeWriteStream' => RemoveWriteStreamEvent::class,
        'removeStream' => RemoveStreamEvent::class,
        'addTimer' => AddTimerEvent::class,
        'addPeriodicTimer' => AddPeriodicTimerEvent::class,
        'cancelTimer' => CancelTimerEvent::class,
        'isTimerActive' => IsTimerActiveEvent::class,
        'nextTick' => NextTickEvent::class,
        'futureTick' => FutureTickEvent::class,
        'tick' => TickEvent::class,
        'run' => RunEvent::class,
        'stop' => StopEvent::class,
    ];

    /**
     * @var LoopInterface
     */
    private $loop;

    /**
     * @var Event[]
     */
    public $events;

    /**
     * @var Event
     */
    public $parentEvent;

    public function __construct(LoopInterface $loop)
    {
        $this->loop = $loop;
        $this->events = [];
        $this->parentEvent = null;
    }

    public function recordCallbackFired($callable, Event $parentEvent)
    {
        $event = new CallbackFiredEvent($callable);
        $event->setParentEvent($parentEvent);
        return $this->recordEvent($event, $callable);
    }

    private function recordEvent(Event $event, $callable)
    {
        $this->events[] = $event;
        $result = null;
        $currentEvent = $this->parentEvent;
        try {
            $event->start();
            $this->parentEvent = $event;
            $result = is_callable($callable) ? $callable() : call_user_func_array(...$callable);
            $event->complete($result);
        } catch (\Throwable $e) {
            $event->fail($e);
            throw $e;
        } finally {
            $this->parentEvent = $currentEvent;
        }

        return $result;
    }

    public function __call($name, $arguments)
    {
        if (!isset(self::PROXY_METHODS[$name])) {
            throw new \BadMethodCallException($name);
        }

        $className = self::PROXY_METHODS[$name];
        /** @var Event $event */
        $event = new $className(...$arguments);
        if ($this->parentEvent) {
            $event->setParentEvent($this->parentEvent);
        }
        $profiler = $this;
        for ($i = 0; $i < count($arguments); $i++) {
            if (is_callable($arguments[$i])) {
                $callable = $arguments[$i];
                $arguments[$i] = function () use (&$profiler, $callable, &$event) {
                    return $profiler->recordCallbackFired($callable, $event);
                };
            }
        }

        return $this->recordEvent($event, [[$this->loop, $name], $arguments]);
    }
}
