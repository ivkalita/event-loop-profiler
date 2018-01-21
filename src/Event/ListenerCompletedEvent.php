<?php


namespace Kaduev13\EventLoopProfiler\Event;

use Kaduev13\EventLoopProfiler\Proxy\ListenerProxy;

class ListenerCompletedEvent extends ListenerEvent
{
    /**
     * @var mixed
     */
    protected $result;

    public function __construct(ListenerProxy $listener, $result)
    {
        parent::__construct($listener);
        $this->result = $result;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }

    public static function getName()
    {
        return 'listener.completed';
    }
}
