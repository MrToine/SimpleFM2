<?php

namespace Framework\Actions;
use GuzzleHttp\Psr7\Response;

/**
 * Rajoute des m�thodes li�es � l'utilisation du Router
 */
trait RouterAwareAction
{
    /**
     * Renvoie une r�ponse de redirection
     * 
     * @param string $path 
     * @param array $params 
     * @return \Psr\Http\Message\MessageInterface
     */
    public function redirect(string $path, array $params = []): Response
    {
        $redirectUri = $this->router->generateUri($path, $params);

        return (new Response())
            ->withStatus(301)
            ->withHeader('Location', $redirectUri);
    }
}