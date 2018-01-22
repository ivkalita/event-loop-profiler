<?php

namespace Kaduev13\EventLoopProfiler;

use Kaduev13\EventLoopProfiler\Proxy\LoopProxy;
use React\EventLoop\LoopInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class Profiler
 *
 * Entry point for profiling.
 */
class Profiler
{
    /**
     * Wraps real react-php event-loop instance with LoopProxy that tracks all
     * the loop activities.
     *
     * @param LoopInterface $loop
     * @param EventSubscriberInterface[] $subscribers
     *
     * @return LoopProxy
     */
    public static function profile(LoopInterface $loop, array $subscribers = [])
    {
        $proxy = new LoopProxy($loop, $subscribers);

        return $proxy;
    }
}
