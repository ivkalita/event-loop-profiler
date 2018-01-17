<?php
/**
 * @author Ivan Kalita
 */

namespace Kaduev13\EventLoopProfiler;

use Kaduev13\EventLoopProfiler\Proxy\LoopProxy;
use React\EventLoop\LoopInterface;

class Profiler
{
    public static function profile(LoopInterface $loop)
    {
        return new LoopProxy($loop);
    }
}
