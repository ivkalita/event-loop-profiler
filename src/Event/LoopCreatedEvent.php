<?php


namespace Kaduev13\EventLoopProfiler\Event;

use React\EventLoop\LoopInterface;

class LoopCreatedEvent extends Event
{
    /**
     * @var LoopInterface
     */
    private $loop;

    public static function getName()
    {
        return 'loop_created';
    }

    public function __construct(LoopInterface $loop)
    {
        parent::__construct();
        $this->loop = $loop;
    }

    public function getLoop(): LoopInterface
    {
        return $this->loop;
    }
}
