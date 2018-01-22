<?php


namespace Kaduev13\EventLoopProfiler\Proxy;

use Kaduev13\EventLoopProfiler\Event\ListenerCompletedEvent;
use Kaduev13\EventLoopProfiler\Event\ListenerEvent;
use Kaduev13\EventLoopProfiler\Event\ListenerFailedEvent;
use Kaduev13\EventLoopProfiler\Event\ListenerStartedEvent;
use React\EventLoop\Timer\TimerInterface;

class TimerProxy extends Proxy implements TimerInterface
{
    /**
     * @var ListenerProxy
     */
    private $listener;

    /**
     * @var TimerInterface
     */
    private $timer;

    /**
     * TimerProxy constructor.
     *
     * @param TimerInterface $timer
     * @param ListenerProxy $listener
     */
    public function __construct(TimerInterface $timer, ListenerProxy $listener)
    {
        $this->listener = $listener;
        $this->timer = $timer;

        parent::__construct();

        $forwardEvent = function (ListenerEvent $event) {
            $this->dispatcher->dispatch($event::getName(), $event);
        };
        $listener->dispatcher->addListener(ListenerStartedEvent::getName(), $forwardEvent);
        $listener->dispatcher->addListener(ListenerCompletedEvent::getName(), $forwardEvent);
        $listener->dispatcher->addListener(ListenerFailedEvent::getName(), $forwardEvent);
    }

    public function getListener(): ListenerProxy
    {
        return $this->listener;
    }

    public function getTimer(): TimerInterface
    {
        return $this->timer;
    }

    protected function generateIdentifier()
    {
        return sprintf(
            "%s_%s",
            $this->isPeriodic() ? 'PeriodicTimer' : 'Timer',
            substr(md5(self::$idGenerator++), 0, 5)
        );
    }


    public function getLoop()
    {
        return $this->timer->getLoop();
    }

    public function getInterval()
    {
        return $this->timer->getInterval();
    }

    public function getCallback()
    {
        return $this->timer->getCallback();
    }

    public function setData($data)
    {
        return $this->timer->setData($data);
    }

    public function getData()
    {
        return $this->timer->getData();
    }

    public function isPeriodic()
    {
        return $this->timer->isPeriodic();
    }

    public function isActive()
    {
        return $this->timer->isActive();
    }

    public function cancel()
    {
        return $this->timer->cancel();
    }
}
