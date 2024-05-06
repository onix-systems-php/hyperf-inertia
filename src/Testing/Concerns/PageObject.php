<?php

declare(strict_types=1);
/**
 * This file is part of the Inertia library for Hyperf.
 *
 * @license  https://github.com/onix-systems-php/hyperf-inertia/blob/main/LICENSE
 */

namespace OnixSystemsPHP\HyperfInertia\Testing\Concerns;

use Hyperf\Collection\Arr;
use Hyperf\ViewEngine\Finder;
use PHPUnit\Framework\Assert as PHPUnit;

use function Hyperf\Config\config;
use function Hyperf\Support\make;

trait PageObject
{
    public function component(?string $value = null, $shouldExist = null): self
    {
        PHPUnit::assertSame($value, $this->component, 'Unexpected Inertia page component.');

        if ($shouldExist || (is_null($shouldExist) && config('inertia.testing.ensure_pages_exist', true))) {
            try {
                $fileViewFinder = make(Finder::class);
                $fileViewFinder->find($value);
            } catch (\InvalidArgumentException $exception) {
                PHPUnit::fail(sprintf('Inertia page component file [%s] does not exist.', $value));
            }
        }

        return $this;
    }

    public function url(string $value): self
    {
        PHPUnit::assertSame($value, $this->url, 'Unexpected Inertia page url.');

        return $this;
    }

    public function version(string $value): self
    {
        PHPUnit::assertSame($value, $this->version, 'Unexpected Inertia asset version.');

        return $this;
    }

    public function toArray(): array
    {
        return [
            'component' => $this->component,
            'props' => $this->props,
            'url' => $this->url,
            'version' => $this->version,
        ];
    }

    protected function prop(?string $key = null)
    {
        return Arr::get($this->props, $key);
    }
}
