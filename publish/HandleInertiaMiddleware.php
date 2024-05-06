<?php
declare(strict_types=1);

namespace App\Common\Middleware;

// example, you need to install a HyperfCore and HyperfAuth packages to use this provider
// use OnixSystemsPHP\HyperfCore\Contract\CoreAuthenticatableProvider;
use OnixSystemsPHP\HyperfInertia\Middleware;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;

class HandleInertiaMiddleware extends Middleware
{
    public function __construct(
        protected ContainerInterface $container,
        // protected CoreAuthenticatableProvider $coreAuthenticatableProvider, example
    ) {
        parent::__construct($container);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(ServerRequestInterface $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                // 'user' => fn () => $this->coreAuthenticatableProvider->user(),
            ],
            'flash' => [
                'alert' => fn () => $this->session->get('alert'),
            ],
        ]);
    }
}
