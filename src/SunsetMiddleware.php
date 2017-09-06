<?php namespace HSkrasek\Sunset;

use Psr\Log\LoggerInterface;

class SunsetMiddleware
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}
