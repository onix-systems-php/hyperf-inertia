<?php

declare(strict_types=1);
/**
 * This file is part of the Inertia library for Hyperf.
 *
 * @license  https://github.com/onix-systems-php/hyperf-inertia/blob/main/LICENSE
 */
use Hyperf\Context\ApplicationContext;
use Hyperf\Context\Context;
use Hyperf\Contract\Arrayable;
use Hyperf\Contract\SessionInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use OnixSystemsPHP\HyperfInertia\Inertia;

use function Hyperf\Collection\collect;

if (! function_exists('inertia')) {
    /**
     * Inertia helper.
     *
     * @param null|string $component
     * @param array|Arrayable $props
     */
    function inertia($component = null, $props = [])
    {
        /** @var Inertia $inertia */
        $inertia = Context::get(Inertia::class, new Inertia());

        if ($component) {
            return $inertia->render($component, $props);
        }

        return $inertia;
    }
}

if (! function_exists('redirect_with')) {
    function redirect_with($path = '/', $data = [])
    {
        $response = ApplicationContext::getContainer()->get(ResponseInterface::class);
        $session = ApplicationContext::getContainer()->get(SessionInterface::class);
        collect($data)->each(fn ($value, $key) => $session->flash($key, $value));

        return $response->redirect($path);
    }
}
if (! function_exists('inertia_location')) {
    /**
     * Inertia location helper.
     *
     * @param string $url
     */
    function inertia_location($url)
    {
        /** @var Inertia $inertia */
        $inertia = Context::get(Inertia::class, new Inertia());
        return $inertia->location($url);
    }
}
