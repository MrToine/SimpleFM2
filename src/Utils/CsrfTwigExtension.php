<?php
namespace Utils;

use Framework\Middleware\CsrfMiddleware;

class CsrfTwigExtension extends \Twig\Extension\AbstractExtension
{
    private $csrfMiddleware;

    public function __construct(CsrfMiddleware $csrfMiddleware)
    {
        $this->csrfMiddleware = $csrfMiddleware;
    }

    public function getFunctions()
    {
        return [
            new \Twig\TwigFunction('csrf_input', [$this, 'csrfInput'], ['is_safe' => 'html'])
        ];
    }

    public function csrfInput()
    {
        return '<input type="hidden" ' .
            'name="' . $this->csrfMiddleware->getFormKey() . '" ' .
            'value="' . $this->csrfMiddleware->generateToken() . '"/>';
    }
}
