<?php

declare(strict_types=1);
/**
 * This file is part of the Inertia library for Hyperf.
 *
 * @license  https://github.com/onix-systems-php/hyperf-inertia/blob/main/LICENSE
 */

namespace OnixSystemsPHP\HyperfInertia;

use Hyperf\Context\Context;
use Psr\Http\Message\ServerRequestInterface;

class Controller
{
    public function __invoke(ServerRequestInterface $request): InertiaResponse
    {
        /** @var Inertia $inertia */
        $inertia = Context::get(Inertia::class, new Inertia());
        return $inertia->render(
            $request->route()->defaults['component'],
            $request->route()->defaults['props']
        );
    }
}
