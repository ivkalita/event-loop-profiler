<?php


namespace Kaduev13\EventLoopProfiler\Processor;

use Kaduev13\EventLoopProfiler\Event\Event;
use Kaduev13\EventLoopProfiler\Event\LoopCreatedEvent;
use Symfony\Component\Console\Output\OutputInterface;

class EventPrinter implements EventProcessorInterface
{
    /**
     * @var int
     */
    protected $loopCreatedAt;

    /**
     * @var OutputInterface
     */
    protected $output;

    public function __construct(OutputInterface $output)
    {
        $this->loopCreatedAt = 0;
        $this->output = $output;
    }

    public function process(Event $event)
    {
        if ($event instanceof LoopCreatedEvent) {
            $this->loopCreatedAt = $event->getCreatedAt();
        }

        $this->printEvent($event);
    }

    private function printEvent(Event $event)
    {
        $this->output->writeln(sprintf(
            '<info>%s %s</info>',
            $event->getCreatedAt() - $this->loopCreatedAt,
            get_class($event)
        ));
    }
}
