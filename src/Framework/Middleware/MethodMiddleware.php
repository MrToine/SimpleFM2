<?php
namespace Framework\Middleware;

class MethodMiddleware
{
    public function __invoke(\Psr\Http\Message\ServerRequestInterface $request, $next)
    {
        $parsedMethod = $request->getParsedBody();

        if (array_key_exists('_METHOD', $parsedMethod) && in_array($parsedMethod['_METHOD'], ['DELETE', 'PUT'])) {
            $request = $request->withMethod($parsedMethod['_METHOD']);
        }

        return $next($request);
    }
}
