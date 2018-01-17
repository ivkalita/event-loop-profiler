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
        $loop->addTimer(0.2, function () {
        });

        $loop->run();

        foreach ($loop->events as $event) {
            echo sprintf(
                "%s %s %s\n",
                get_class($event),
                $event->getTime(),
                $event->getStatus()
            );
        }
    }
}
