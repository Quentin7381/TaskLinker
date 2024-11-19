<?php
namespace App\Twig;

use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    private $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('route_is_active', [$this, 'routeIsActive']),
        ];
    }

    public function routeIsActive(array $routes): bool
    {
        $currentRoute = $this->requestStack->getCurrentRequest()->attributes->get('_route');
        foreach($routes as $route) {
            // Manage wildcards
            if (str_contains($route, '*')) {
                $route = str_replace('.', '\.', $route);
                $route = str_replace('*', '.*', $route);
                $route = '/^' . $route . '$/';
                if (preg_match($route, $currentRoute)) {
                    return true;
                }
            } else {
                if ($currentRoute === $route) {
                    return true;
                }
            }
        }

        return false;
    }
}