<?php


namespace Kaduev13\EventLoopProfiler\Processor;

use Kaduev13\EventLoopProfiler\Event\Event;

interface EventProcessorInterface
{
    public function process(Event $event);
}
