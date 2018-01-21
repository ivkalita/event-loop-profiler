<?php


namespace Kaduev13\EventLoopProfiler\Proxy;

use Symfony\Component\EventDispatcher\EventDispatcher;

class Proxy
{
    /**
     * @var int
     */
    public static $idGenerator = 1;

    /**
     * @var EventDispatcher
     */
    public $dispatcher;

    /**
     * @var string
     */
    private $identifier;

    public function __construct()
    {
        $this->identifier = $this->generateIdentifier();
        $this->dispatcher = new EventDispatcher();
    }

    /**
     * @return string
     */
    protected function generateIdentifier()
    {
        return sprintf(
            "%s_%s",
            (new \ReflectionClass($this))->getShortName(),
            substr(md5(self::$idGenerator++), 0, 5)
        );
    }

    /**
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }
}
