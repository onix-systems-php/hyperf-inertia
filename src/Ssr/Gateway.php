<?php

declare(strict_types=1);
/**
 * This file is part of the Inertia library for Hyperf.
 *
 * @license  https://github.com/onix-systems-php/hyperf-inertia/blob/main/LICENSE
 */

namespace OnixSystemsPHP\HyperfInertia\Ssr;

interface Gateway
{
    /**
     * Dispatch the Inertia page to the Server Side Rendering engine.
     */
    public function dispatch(array $page): ?Response;
}
