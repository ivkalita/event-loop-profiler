<?php

namespace Kaduev13\EventLoopProfiler\Event;

use Symfony\Component\EventDispatcher\Event as SymfonyEvent;

/**
 * Class Event
 *
 * Base entity for storing runtime loop events.
 */
abstract class Event extends SymfonyEvent
{
    /**
     * @var float
     */
    protected $createdAt;

    /**
     * @return string
     */
    abstract public static function getName();

    public function __construct()
    {
        $this->createdAt = microtime(true);
    }

    /**
     * @return float
     */
    public function getCreatedAt(): float
    {
        return $this->createdAt;
    }
}
