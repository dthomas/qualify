<?php

namespace App\Twig;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('arrow', [$this, 'arrowDirection']),
            new TwigFunction('sorted_filter_path', [$this, 'sortedFilterPath']),
        ];
    }

    public function arrowDirection(string $column, string $expected, string $direction)
    {
        if ($column !== $expected) {
            return '';
        }

        return $direction === 'asc' ? '↑' : '↓';
    }

    public function sortedFilterPath(string $route, int $limit, int $page, string $sort, string $dir)
    {
        return $this
            ->urlGenerator
            ->generate($route, [
                'limit' => $limit,
                'page' => $page,
                'sort' => $sort,
                'dir' => $dir === 'asc' ? 'desc' : 'asc',
            ]);
    }
}
