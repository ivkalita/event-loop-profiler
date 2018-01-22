<?php


namespace Kaduev13\EventLoopProfiler\Subscribers;


use Kaduev13\EventLoopProfiler\Event\TimerAddedEvent;
use Kaduev13\EventLoopProfiler\Event\TimerCancelledEvent;
use Kaduev13\EventLoopProfiler\Event\TimerListenerEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class RuntimePrintSubscriber implements EventSubscriberInterface
{
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
}