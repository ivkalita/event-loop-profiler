<?php

namespace Kaduev13\EventLoopProfiler\Event;

use Symfony\Component\EventDispatcher\Event as SymfonyEvent;

/**
 * Class Event
 *
 * Base entity for storing runtime loop events.
 */
class Event extends SymfonyEvent
{
    /**
     * @var int
     */
    public static $idGenerator = 1;

    const STATUS_NOT_STARTED = 'NOT_STARTED';
    const STATUS_STARTED = 'STARTED';
    const STATUS_COMPLETED = 'COMPLETED';
    const STATUS_FAILED = 'FAILED';

    /**
     * When event was started.
     *
     * @var float
     */
    protected $startedAt;

    /**
     * When event was ended (failed/succeeded).
     *
     * @var float
     */
    protected $endedAt;

    /**
     * If any throwable happened during event execution, it will be recorded to
     * this member.
     *
     * @var \Throwable
     */
    protected $error;

    /**
     * @var string
     */
    protected $status = Event::STATUS_NOT_STARTED;

    /**
     * User listener/loop method return value if any.
     *
     * @var mixed
     */
    protected $result;

    /**
     * Unique event identifier.
     *
     * @var int
     */
    protected $id;

    /**
     * Event name (may be non-unique through all other events).
     *
     * @var string
     */
    protected $name;

    /**
     * If this event is nested, "parent" event will be recorded.
     *
     * @var Event|null
     */
    protected $context;

    public function __construct()
    {
        $this->id = self::$idGenerator++;
        $this->name = sprintf('%s-%s', static::class, $this->id);
    }

    public function start()
    {
        $this->startedAt = microtime(true);
        $this->status = self::STATUS_STARTED;
    }

    public function complete($result = null)
    {
        $this->status = Event::STATUS_COMPLETED;
        $this->result = $result;
        $this->end();
    }

    public function fail(\Throwable $e)
    {
        $this->error = $e;
        $this->status = Event::STATUS_FAILED;
        $this->end();
    }

    private function end()
    {
        $this->endedAt = microtime(true);
    }

    /**
     * @return mixed
     */
    public function getStartedAt()
    {
        return $this->startedAt;
    }

    /**
     * @return mixed
     */
    public function getEndedAt()
    {
        return $this->endedAt;
    }

    public function getTime()
    {
        if ($this->endedAt === null || $this->startedAt === null) {
            return null;
        }

        return $this->endedAt - $this->startedAt;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return Event|null
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param Event|null $context
     */
    public function setContext(Event $context = null)
    {
        $this->context = $context;
    }
}
