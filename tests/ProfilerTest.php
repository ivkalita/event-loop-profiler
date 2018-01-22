<?php

namespace Tests\Kaduev13\EventLoopProfiler;

use Kaduev13\EventLoopProfiler\Profiler;
use Kaduev13\EventLoopProfiler\Subscribers\RuntimePrintSubscriber;
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
        $loop->dispatcher->addSubscriber(new RuntimePrintSubscriber());

        $timer1 = $loop->addPeriodicTimer(0.2, function () use (&$loop) {
        });

        $loop->addTimer(1.0, function () use (&$loop, &$timer1) {
            $loop->addTimer(2.0, function () use (&$loop, &$timer1) {
            });
            $loop->addTimer(3.0, function () use (&$loop, &$timer1) {
            });
            $loop->cancelTimer($timer1);
        });

        $loop->run();

        $this->assertEquals(1, 1);
    }
}
