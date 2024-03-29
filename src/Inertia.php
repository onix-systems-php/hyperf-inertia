<?php

declare(strict_types=1);
/**
 * This file is part of the extension library for Hyperf.
 *
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace OnixSystemsPHP\HyperfInertia;

use Hyperf\Collection\Arr;
use Hyperf\Context\ApplicationContext;
use Hyperf\Contract\Arrayable;
use Hyperf\HttpMessage\Uri\Uri;
use Hyperf\HttpMessage\Base\Response;
use Hyperf\Macroable\Macroable;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use function Hyperf\Config\config;
use function Hyperf\Support\call;

class Inertia
{
    use Macroable;
    protected string $rootView = 'app';

    protected array $sharedProps = [];

    protected null|\Closure|string $version = null;

    public function __construct(private readonly ServerRequestInterface $request)
    {
        $this->rootView = config('inertia.root_view', $this->rootView);
    }

    public function setRootView(string $name): void
    {
        $this->rootView = $name;
    }

    public function share(array|Arrayable|string $key, mixed $value = null): void
    {
        if (is_array($key)) {
            $this->sharedProps = array_merge($this->sharedProps, $key);
        } elseif ($key instanceof Arrayable) {
            $this->sharedProps = array_merge($this->sharedProps, $key->toArray());
        } else {
            Arr::set($this->sharedProps, $key, $value);
        }
    }

    public function getShared(string $key = null, mixed$default = null): mixed
    {
        if ($key) {
            return Arr::get($this->sharedProps, $key, $default);
        }

        return $this->sharedProps;
    }

    public function flushShared(): void
    {
        $this->sharedProps = [];
    }

    public function version(null|\Closure|string $version): void
    {
        $this->version = $version;
    }

    public function getVersion(): string
    {
        $version = $this->version instanceof \Closure
            ? call($this->version)
            : $this->version;

        return (string) $version;
    }

    public function lazy(callable $callback): LazyProp
    {
        return new LazyProp($callback);
    }
    public function render(string $component, array|Arrayable $props = []): InertiaResponse
    {
        if ($props instanceof Arrayable) {
            $props = $props->toArray();
        }

        return new InertiaResponse(
            $component,
            array_merge($this->sharedProps, $props),
            $this->rootView,
            $this->getVersion()
        );
    }

    public function location( string|Uri $url): ResponseInterface
    {
        $response = ApplicationContext::getContainer()->get(Response::class);
        if ($this->request->hasHeader('X-Inertia')) {
            return $response
                ->withStatus(409)
                ->withAddedHeader('X-Inertia-Location', $url instanceof Uri ? $url->toString() : $url)
                ->withBody(new \Hyperf\HttpMessage\Stream\SwooleStream(''));
        }

        return $url instanceof Uri ? $url->toString() : Redirect::away($url); // notice refactor to hyperf
    }
}
