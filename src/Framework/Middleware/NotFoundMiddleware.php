<?php
namespace Framework\Middleware;

use GuzzleHttp\Psr7\Response;

class NotFoundMiddleware
{
    public function __invoke(\Psr\Http\Message\ServerRequestInterface $request, $next)
    {
        return new Response(404, [], '<h1 style="color:red">Erreur 404</h1>Aucune page n\'est disponible ici');
    }
}
