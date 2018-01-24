<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Kaduev13\EventLoopProfiler\Profiler;
use Kaduev13\EventLoopProfiler\Subscribers\PipeSubscriber;
use \Kaduev13\EventLoopProfiler\Processor\EventPrinter;
use React\EventLoop\Factory as LoopFactory;
use Symfony\Component\Console\Output\StreamOutput;

// Creating a real loop instance
$realLoop = LoopFactory::create();

// Creating profiler and setup subscribers
$output = new StreamOutput(STDOUT);
$printer = new EventPrinter($output);
$loop = Profiler::profile($realLoop, [new PipeSubscriber($printer)]);

// Setup loop
$timer1 = $loop->addPeriodicTimer(0.2, function () use (&$loop) {
});

$loop->addTimer(1.0, function () use (&$loop, &$timer1) {
    $loop->addTimer(2.0, function () use (&$loop, &$timer1) {
    });
    $loop->addTimer(3.0, function () use (&$loop, &$timer1) {
    });
    $loop->cancelTimer($timer1);
});

// Run the loop
$loop->run();
