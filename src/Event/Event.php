<?php
/**
 * @author Ivan Kalita
 */

namespace Kaduev13\EventLoopProfiler\Event;

class Event
{
    const STATUS_NOT_STARTED = 'NOT_STARTED';
    const STATUS_STARTED = 'STARTED';
    const STATUS_COMPLETED = 'COMPLETED';
    const STATUS_FAILED = 'FAILED';

    protected $startedAt;
    protected $endedAt;
    protected $status = Event::STATUS_NOT_STARTED;
    protected $error;
    protected $result;

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
}
