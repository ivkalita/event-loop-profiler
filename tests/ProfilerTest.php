<?php
/**
 * @author Ivan Kalita
 */

namespace Tests\Kaduev13\EventLoopProfiler;

use Kaduev13\EventLoopProfiler\Profiler;
use PHPUnit\Framework\TestCase;
use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;

class ProfilerTest extends TestCase
{
    /**
     * @var LoopInterface
     */
    private $loop;

    public function setUp()
    {
        $this->loop = Factory::create();
    }

    public function testProfile()
    {
        $loop = Profiler::profile($this->loop);
        $timer1 = $loop->addPeriodicTimer(0.2, function () use (&$loop) {
        });

        $loop->addTimer(1.0, function() use (&$loop, &$timer1) {
            $loop->cancelTimer($timer1);
        });

        $loop->run();

        foreach ($loop->events as $event) {
            echo sprintf(
                "%s –> %s %s\n",
                $event->getParentEvent() ? $event->getParentEvent()->getName() : 'Loop',
                $event->getName(),
                $event->getTime(),
                $event->getStatus()
            );
        }
    }
}
