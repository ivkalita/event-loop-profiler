<?php


namespace Kaduev13\EventLoopProfiler\Internal;

use React\EventLoop\ExtEventLoop;
use React\EventLoop\LibEventLoop;
use React\EventLoop\LoopInterface;
use React\EventLoop\StreamSelectLoop;

class LoopBackendGuesser
{
    const EXT_EVENT_LOOP = 'ExtEventLoop';
    const EXT_LIBEVENT_LOOP = 'ExtLibeventLoop';
    const STREAM_SELECT_LOOP = 'StreamSelectLoop';

    /**
     * @param LoopInterface $loop
     *
     * @return null|string
     */
    public static function guess(LoopInterface $loop)
    {
        switch (true) {
            case $loop instanceof ExtEventLoop:
                return self::EXT_EVENT_LOOP;
            case $loop instanceof LibEventLoop:
                return self::EXT_LIBEVENT_LOOP;
            case $loop instanceof StreamSelectLoop:
                return self::EXT_EVENT_LOOP;
            default:
                return null;
        }
    }
}
