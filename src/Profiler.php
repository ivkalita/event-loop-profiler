<?php

namespace Kaduev13\EventLoopProfiler;

use Kaduev13\EventLoopProfiler\Event\Event;
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
     * @param bool $runtime – if true than all events info will be printed to
     *  stdout in runtime.
     *
     * @return LoopProxy
     */
    public static function profile(LoopInterface $loop, bool $runtime = false)
    {
        $proxy = new LoopProxy($loop);
        if ($runtime) {
            $stdout = fopen('php://stdout', 'w');
            $proxy->dispatcher->addListener('loop_proxy.event_started', function (Event $event) use (&$stdout) {
                fwrite($stdout, sprintf(
                    "STARTED: %s –> %s %s\n",
                    $event->getContext() ? $event->getContext()->getName() : 'Loop',
                    $event->getName(),
                    $event->getStartedAt()
                ));
            });
            $proxy->dispatcher->addListener('loop_proxy.event_completed', function (Event $event) use (&$stdout) {
                fwrite($stdout, sprintf(
                    "COMPLETED: %s –> %s %s\n",
                    $event->getContext() ? $event->getContext()->getName() : 'Loop',
                    $event->getName(),
                    $event->getEndedAt()
                ));
            });
            $proxy->dispatcher->addListener('loop_proxy.event_failed', function (Event $event) use (&$stdout) {
                fwrite($stdout, sprintf(
                    "FAILED: %s –> %s %s\n",
                    $event->getContext() ? $event->getContext()->getName() : 'Loop',
                    $event->getName(),
                    $event->getEndedAt()
                ));
            });
        }

        return $proxy;
    }
}
