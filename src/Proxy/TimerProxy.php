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
    public $listenerProxy;

    /**
     * @var TimerInterface
     */
    public $timer;

    /**
     * TimerProxy constructor.
     *
     * @param TimerInterface $timer
     * @param ListenerProxy $listenerProxy
     */
    public function __construct(TimerInterface $timer, ListenerProxy $listenerProxy)
    {
        $this->listenerProxy = $listenerProxy;
        $this->timer = $timer;

        parent::__construct();

        $forwardEvent = function (ListenerEvent $event) {
            $this->dispatcher->dispatch($event::getName(), $event);
        };
        $listenerProxy->dispatcher->addListener(ListenerStartedEvent::getName(), $forwardEvent);
        $listenerProxy->dispatcher->addListener(ListenerCompletedEvent::getName(), $forwardEvent);
        $listenerProxy->dispatcher->addListener(ListenerFailedEvent::getName(), $forwardEvent);
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
