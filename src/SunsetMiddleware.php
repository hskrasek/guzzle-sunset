<?php namespace HSkrasek\Sunset;

use DateTime;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
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

    /**
     * @param callable $handler
     *
     * @return callable
     */
    public function __invoke(callable $handler): callable
    {
        return function (RequestInterface $request, array $options) use ($handler) {
            return $handler($request, $options)->then(function (ResponseInterface $response) use ($request) {
                if ($this->responseHasSunsetHeader($response)) {
                    $deprecationDate = new DateTime($response->getHeader('Sunset')[0]);
                    $this->reportDeprecation($request, $deprecationDate);
                }

                return $response;
            });
        };
    }

    /**
     * Determine if a Sunset header exists on the response.
     *
     * @param ResponseInterface $response
     *
     * @return bool
     */
    private function responseHasSunsetHeader(ResponseInterface $response): bool
    {
        return !empty($response->getHeader('Sunset'));
    }

    /**
     * Report the deprecation provided by the Sunset header.
     *
     * @param RequestInterface $request
     * @param DateTime $deprecationDate
     */
    private function reportDeprecation(RequestInterface $request, DateTime $deprecationDate): void
    {
        if ($deprecationDate > new DateTime) {
            $this->logger->warning(
                "Endpoint {$request->getUri()} is deprecated for removal on {$deprecationDate->format('c')}"
            );
        } else {
            $this->logger->warning(
                "Endpoint {$request->getUri()} was deprecated for removal on {$deprecationDate->format('c')} and could be removed AT ANY TIME"
            );
        }
    }
}
