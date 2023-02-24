<?php
namespace Framework\Middleware;

class TrailingSlashMiddleware
{
    public function __invoke(\Psr\Http\Message\ServerRequestInterface $request, callable $next)
    {
        // On récupère le chemin de l'URL
        $uri = $request->getUri()->getPath();

        // On vérifie si l'URL se termine par un '/'
        if (!empty($uri) && $uri[-1] === "/") {
            // Si oui, on redirige l'utilisateur sans le '/'
            return (new \GuzzleHttp\Psr7\Response())
                ->withStatus(301)
                ->withHeader('Content-Type: text/html; charset=utf-8')
                ->withHeader('Location', substr($uri, 0, -1));
        }

        return $next($request);
    }
}
