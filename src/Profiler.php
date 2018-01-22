<?php

namespace Kaduev13\EventLoopProfiler;

use Kaduev13\EventLoopProfiler\Proxy\LoopProxy;
use React\EventLoop\LoopInterface;

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
     *
     * @return LoopProxy
     */
    public static function profile(LoopInterface $loop)
    {
        $proxy = new LoopProxy($loop);

        return $proxy;
    }
}
