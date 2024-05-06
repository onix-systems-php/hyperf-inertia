<?php

declare(strict_types=1);
/**
 * This file is part of the Inertia library for Hyperf.
 *
 * @license  https://github.com/onix-systems-php/hyperf-inertia/blob/main/LICENSE
 */

namespace OnixSystemsPHP\HyperfInertia;

use Hyperf\Context\Context;
use Hyperf\ViewEngine\Http\Middleware\ValidationExceptionHandle as BaseValidationExceptionHandler;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function Hyperf\Collection\collect;
use function Hyperf\Config\config;

class Middleware extends BaseValidationExceptionHandler
{
    protected string $rootView = 'app';

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $url = $request->getUri()->getPath();
        $this->session->setPreviousUrl($url);

        if ($this->isSkipped($url)) {
            return $handler->handle($request);
        }

        $inertia = new Inertia();
        $inertia->version(fn () => $this->version($request));
        $inertia->share($this->share($request));
        Context::set(Inertia::class, $inertia);

        $response = parent::process($request, $handler);

        $response->setHeader('Vary', 'X-Inertia');

        if (! $request->hasHeader('X-Inertia')) {
            return $response;
        }
        if ($request->getMethod() === 'GET'
            && $request->header('X-Inertia-Version', '') !== $inertia->getVersion()
        ) {
            $response = $this->onVersionChange($request, $response);
        }

        if ($response->getStatusCode() === 200 && empty($response->getBody())) {
            $response = $this->onEmptyResponse($request, $response);
        }

        if ($response->getStatusCode() === 302 && in_array($request->getMethod(), ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            $response = $response->withStatus(303);
        }

        $this->session->setPreviousUrl('');

        return $response;
    }

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(ServerRequestInterface $request): ?string
    {
        if (config('app.asset_url')) {
            return md5(config('app.asset_url'));
        }

        if (file_exists($manifest = Vite::getPublicPath('/mix-manifest.json'))) {
            return md5_file($manifest);
        }

        if (file_exists($manifest = Vite::getPublicPath('/build/manifest.json'))) {
            return md5_file($manifest);
        }

        return null;
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     */
    public function share(ServerRequestInterface $request): array
    {
        return [
            'errors' => function () use ($request) {
                return $this->resolveValidationErrors($request);
            },
        ];
    }

    /**
     * Sets the root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     */
    public function rootView(): string
    {
        return config('inertia.root_view', $this->rootView);
    }

    /**
     * Determines what to do when an Inertia action returned with no response.
     * By default, we'll redirect the user back to where they came from.
     */
    public function onEmptyResponse(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        return $response->redirect($request->getUri()->getPath());
    }

    /**
     * Determines what to do when the Inertia asset version has changed.
     * By default, we'll initiate a client-side location visit to force an update.
     */
    public function onVersionChange(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $inertia = Context::get(Inertia::class);
        return $inertia->location($request->getUri());
    }

    /**
     * Resolves and prepares validation errors in such
     * a way that they are easier to use client-side.
     */
    public function resolveValidationErrors(ServerRequestInterface $request): object
    {
        if (! $this->session->has('errors')) {
            return (object) [];
        }

        return (object) collect($this->session->get('errors')->getBags())->map(function ($bag) {
            return (object) collect($bag->messages())->map(function ($errors) {
                return $errors[0];
            })->toArray();
        })->pipe(function ($bags) use ($request) {
            if ($bags->has('default') && $request->getHeader('x-inertia-error-bag')) {
                return [$request->header('x-inertia-error-bag') => $bags->get('default')];
            }

            if ($bags->has('default')) {
                return $bags->get('default');
            }

            return $bags->toArray();
        });
    }

    private function isSkipped($request_url): bool
    {
        $segments = explode('/', trim($request_url, '/'));
        $prefix = array_shift($segments) ?? null;

        return $prefix && in_array($prefix, config('inertia.skip_url_prefix'));
    }
}
