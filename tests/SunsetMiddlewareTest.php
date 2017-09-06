<?php

namespace HSkrasek\Sunset\Tests;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use HSkrasek\Sunset\SunsetMiddleware;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class SunsetMiddlewareTest extends TestCase
{
    /**
     * @test
     */
    public function it_does_nothing_when_sunset_header_is_not_present()
    {
        $mockLogger = $this->createMock(LoggerInterface::class);
        $mockLogger->expects($this->never())
            ->method('warning')
            ->with(
                'Endpoint http://www.example.com/deprecated is deprecated for removal on ' . (new \DateTime('Sat, 31 Dec 2018 23:59:59 GMT'))->format('c'),
                []
            );

        $mockHandler = new MockHandler([
            new Response(200, []),
        ]);

        $stack = HandlerStack::create($mockHandler);
        $stack->push(new SunsetMiddleware($mockLogger));

        $client = new Client(['handler' => $stack, 'base_uri' => 'http://www.example.com']);

        $client->request('GET', '/deprecated');
    }

    /**
     * @test
     */
    public function it_logs_a_warning_when_sunset_header_is_present()
    {
        $mockLogger = $this->createMock(LoggerInterface::class);
        $mockLogger->expects($this->once())
            ->method('warning')
            ->with(
                'Endpoint http://www.example.com/deprecated is deprecated for removal on ' . (new \DateTime('Sat, 31 Dec 2018 23:59:59 GMT'))->format('c'),
                []
            );

        $mockHandler = new MockHandler([
            new Response(200, ['Sunset' => 'Sat, 31 Dec 2018 23:59:59 GMT']),
        ]);

        $stack = HandlerStack::create($mockHandler);
        $stack->push(new SunsetMiddleware($mockLogger));

        $client = new Client(['handler' => $stack, 'base_uri' => 'http://www.example.com']);

        $client->request('GET', '/deprecated');
    }
}
