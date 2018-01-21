<?php

namespace Kaduev13\EventLoopProfiler;

use Kaduev13\EventLoopProfiler\Event\TimerAddedEvent;
use Kaduev13\EventLoopProfiler\Event\TimerCancelledEvent;
use Kaduev13\EventLoopProfiler\Event\TimerListenerEvent;
use Kaduev13\EventLoopProfiler\Proxy\LoopProxy;
use React\EventLoop\LoopInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class Profiler
 *
 * Entry point for profiling.
 */
class Profiler
{
    /**
     * Wraps real react-php event-loop instance with LoopProxy that tracks all
     * the loop activities.
     *
     * @param LoopInterface $loop
     * @param bool $runtime – if true than all events info will be printed to
     *  stdout in runtime.
     *
     * @return LoopProxy
     */
    public static function profile(LoopInterface $loop, bool $runtime = false)
    {
        $proxy = new LoopProxy($loop);
        if ($runtime) {
            self::setupRuntimeSubscriber($proxy);
        }

        return $proxy;
    }

    public static function setupRuntimeSubscriber(LoopProxy $loopProxy)
    {
        $loopProxy->dispatcher->addSubscriber(new class implements EventSubscriberInterface {
            public static function getSubscribedEvents()
            {
                return [
                    TimerListenerEvent::getName() => 'onTimerListenerEvent',
                    TimerAddedEvent::getName() => 'onTimerAddedEvent',
                    TimerCancelledEvent::getName() => 'onTimerCancelledEvent',
                ];
            }

            public function printString(string $string)
            {
                $stdout = fopen('php://stdout', 'w');
                fwrite($stdout, $string);
                fclose($stdout);
            }

            public function onTimerListenerEvent(TimerListenerEvent $timerListenerEvent)
            {
                $this->printString(sprintf(
                    "%s %s %s %s\n",
                    $timerListenerEvent->getCreatedAt(),
                    $timerListenerEvent->getTimer()->getIdentifier(),
                    $timerListenerEvent->getEvent()::getName(),
                    $timerListenerEvent->getEvent()->getListener()->getIdentifier()
                ));
            }

            public function onTimerAddedEvent(TimerAddedEvent $timerAddedEvent)
            {
                $this->printString(sprintf(
                    "%s %s %s\n",
                    $timerAddedEvent->getCreatedAt(),
                    $timerAddedEvent::getName(),
                    $timerAddedEvent->getTimer()->getIdentifier()
                ));
            }

            public function onTimerCancelledEvent(TimerCancelledEvent $timerCancelledEvent)
            {
                $this->printString(sprintf(
                    "%s %s %s\n",
                    $timerCancelledEvent->getCreatedAt(),
                    $timerCancelledEvent::getName(),
                    $timerCancelledEvent->getTimer()->getIdentifier()
                ));
            }
        });
    }
}
