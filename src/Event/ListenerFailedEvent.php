<?php


namespace Kaduev13\EventLoopProfiler\Event;

use Kaduev13\EventLoopProfiler\Proxy\ListenerProxy;

class ListenerFailedEvent extends ListenerEvent
{
    /**
     * @var \Throwable
     */
    protected $exception;

    public function __construct(ListenerProxy $listener, \Throwable $exception)
    {
        parent::__construct($listener);
        $this->exception = $exception;
    }

    /**
     * @return \Throwable
     */
    public function getException(): \Throwable
    {
        return $this->exception;
    }

    public static function getName()
    {
        return 'listener.failed';
    }
}
