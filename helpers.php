<?php

declare(strict_types=1);
/**
 * This file is part of the extension library for Hyperf.
 *
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

use Hyperf\Context\ApplicationContext;
use Hyperf\Contract\Arrayable;
use Hyperf\Contract\SessionInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use OnixSystemsPHP\HyperfInertia\Inertia;
use function \Hyperf\Collection\collect;

if (! function_exists('inertia')) {
    /**
     * Inertia helper.
     *
     * @param null|string $component
     * @param array|Arrayable $props
     */
    function inertia($component = null, $props = [])
    {
        $instance = ApplicationContext::getContainer()->get(Inertia::class);

        if ($component) {
            return $instance->render($component, $props);
        }

        return $instance;
    }
}

if (! function_exists('redirect_with')) {
    function redirect_with($path = '/', $data = [])
    {
        $response = ApplicationContext::getContainer()->get(ResponseInterface::class);
        $session = ApplicationContext::getContainer()->get(SessionInterface::class);
        collect($data)->each(fn($value, $key) => $session->flash($key, $value));

        return $response->redirect($path);
    }
}
if (! function_exists('inertia_location')) {
    /**
     * Inertia location helper.
     *
     * @param  string  url
     * @param mixed $url
     */
    function inertia_location($url)
    {
        return ApplicationContext::getContainer()->get(Inertia::class)->location($url);
    }
}
