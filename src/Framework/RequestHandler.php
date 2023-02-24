<?php
namespace Framework;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Container\ContainerInterface;

class RequestHandler implements RequestHandlerInterface
{
    /**
     * Container interface
     * @var ContainerInterface
     */
    private $container;

    /**
     * Request handler
     * @var RequestHandlerInterface
     */
    private $handler;

    public function __construct(ContainerInterface $container, RequestHandlerInterface $handler)
    {
        $this->container = $container;
        $this->handler = $handler;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->handler->handle($request);
    }
}
