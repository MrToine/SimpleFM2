<?php
namespace Utils;

use Twig\TwigFilter;

/**
 * Série d'extension concernant les texte sous twig
 */
class TwigTextExtension extends \Twig\Extension\AbstractExtension
{
    /**
     *
     * @return array<TwigFilter>
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('slice', [$this, 'slice'])
        ];
    }

    /**
     * Renvoi un extrait de contenu avec un nombre de caractère défini par maxLength
     * @param mixed $content
     * @param mixed $maxLength
     * @return mixed
     */
    public function slice(string $content, int $maxLength = 100): string
    {
        if(is_null($content))
        {
            return '';
        }

        if (mb_strlen($content) > $maxLength) {
            $slice = mb_substr($content, 0, $maxLength);
            $lastSpace = mb_strrpos($slice, ' ');
            return mb_substr($slice, 0, $lastSpace) . '...';
        }
        return $content;
    }
}
