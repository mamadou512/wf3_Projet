<?php

namespace App\Twig;

use App\Entity\BlogPost;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [$this, 'doSomething']),
            new TwigFilter('ellipsis', [$this, 'makeEllipsis']),
            new TwigFilter('badge', [$this, 'getBadge'], ['is_safe' => ['html']]),
        ];
    }

    public function makeEllipsis(string $text, int $length = 20): string
    {
        $dots = strlen($text) > $length ? '...' : '';
        return substr($text, 0, $length) . $dots;
    }
    public function getBadge(object $object): string
    {
        if ($object instanceof BlogPost) {
            return match($object->getStatus()) {
                BlogPost::STATUS_DRAFT => '<span class="badge text-bg-secondary">Brouillon</span>',
                BlogPost::STATUS_ACTIVE => '<span class="badge text-bg-success">En ligne</span>',
            };
            
        }
        return '';
    }
}
