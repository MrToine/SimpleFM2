<?php
namespace Framework\Middleware;

use ArrayAccess;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CsrfMiddleware implements MiddlewareInterface
{

    /**
     * clé csrf
     * @var string
     */
    private $sessionKey;

    /**
     * défini la clé csrf
     * @var string
     */
    private $formKey;

    /**
     * Limite de tokens
     *
     * @var integer
     */
    private $limit;
    
    /**
     * Tableau qui contient la liste des tokens csrf
     * @var array
     */
    private $session;

    public function __construct(
        \ArrayAccess &$session,
        int $limit = 50,
        string $formKey = '_csrf',
        string $sessionKey = 'csrf'
    ) {
        $this->session = &$session;
        $this->formKey = $formKey;
        $this->sessionKey = $sessionKey;
        $this->limit = $limit;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (in_array($request->getMethod(), ['POST', 'PUT', 'DELETE'])) {
            $params = $request->getParsedBody() ?: [];
            if (!array_key_exists($this->formKey, $params)) {
                $this->reject();
            } else {
                $csrfList = $this->session[$this->sessionKey] ?? [];
                if (in_array($params[$this->formKey], $csrfList)) {
                    $this->useToken($params[$this->formKey]);
                    return $handler->handle($request);
                } else {
                    $this->reject();
                }
            }
        }
        return $handler->handle($request);
    }

    public function generateToken(): string
    {
        $token = bin2hex(random_bytes(16));
        $csrfList = $this->session[$this->sessionKey] ?? [];
        $csrfList[] = $token;
        $this->session[$this->sessionKey] = $csrfList;
        $this->limitTokens();

        return $token;
    }

    private function reject(): void
    {
        throw new \Framework\Exception\CsrfInvalidException();
    }

    private function useToken($token): void
    {
        $tokens = array_filter($this->session[$this->sessionKey], function ($t) use ($token) {
            return $token !== $t;
        });
        $this->session[$this->sessionKey] = $tokens;
    }

    private function limitTokens(): void
    {
        $tokens = $this->session[$this->sessionKey] ?? [];
        if (count($tokens) > $this->limit) {
            array_shift($tokens);
        }

        $this->session[$this->sessionKey] = $tokens;
    }

    private function validSession($session)
    {
        if (!is_array($session) && $session instanceof \ArrayAccess) {
            throw new \TypeError('La session passé au middleware n\'est pas traitable comme un tableau');
        }
    }

    /**
     * Get défini la clé csrf
     *
     * @return  string
     */
    public function getFormKey(): string
    {
        return $this->formKey;
    }
}
