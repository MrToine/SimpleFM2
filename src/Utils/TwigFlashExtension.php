<?php
namespace Utils;

use Framework\Session\FlashService;

class TwigFlashExtension extends \Twig\Extension\AbstractExtension
{

    private $flashService;

    public function __construct(FlashService $flashService)
    {
        $this->flashService = $flashService;
    }

    public function getFunctions(): array
    {
        return [
            new \Twig\TwigFunction('flash', [$this, 'getFlash']),
        ];
    }

    public function getFlash($type): ?string
    {
        return $this->flashService->get($type);
    }
}
