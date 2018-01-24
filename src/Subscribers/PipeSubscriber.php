<?php


namespace Kaduev13\EventLoopProfiler\Subscribers;

use Kaduev13\EventLoopProfiler\Event\Event;
use Kaduev13\EventLoopProfiler\Event\LoopCreatedEvent;
use Kaduev13\EventLoopProfiler\Event\RunEvent;
use Kaduev13\EventLoopProfiler\Event\TimerAddedEvent;
use Kaduev13\EventLoopProfiler\Event\TimerCancelledEvent;
use Kaduev13\EventLoopProfiler\Event\TimerListenerEvent;
use Kaduev13\EventLoopProfiler\Processor\EventProcessorInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class PipeSubscriber
 */
class PipeSubscriber implements EventSubscriberInterface
{
    /**
     * @var EventProcessorInterface
     */
    private $processor;

    public function __construct(EventProcessorInterface $processor)
    {
        $this->processor = $processor;
    }

    public static function getSubscribedEvents()
    {
        return [
            LoopCreatedEvent::getName() => 'onEvent',
            RunEvent::getName() => 'onEvent',
            TimerAddedEvent::getName() => 'onEvent',
            TimerCancelledEvent::getName() => 'onEvent',
            TimerListenerEvent::getName() => 'onEvent',
        ];
    }

    public function onEvent(Event $event)
    {
        $this->processor->process($event);
    }
}
