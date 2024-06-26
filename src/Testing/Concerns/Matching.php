<?php

declare(strict_types=1);
/**
 * This file is part of the Inertia library for Hyperf.
 *
 * @license  https://github.com/onix-systems-php/hyperf-inertia/blob/main/LICENSE
 */

namespace OnixSystemsPHP\HyperfInertia\Testing\Concerns;

use Hyperf\Collection\Collection;
use Hyperf\Contract\Arrayable;
use Hyperf\Resource\Json\JsonResource;
use PHPUnit\Framework\Assert as PHPUnit;

trait Matching
{
    public function whereAll(array $bindings): self
    {
        foreach ($bindings as $key => $value) {
            $this->where($key, $value);
        }

        return $this;
    }

    /**
     * @param mixed $expected
     * @throws \JsonException
     */
    public function where(string $key, $expected): self
    {
        $this->has($key);

        $actual = $this->prop($key);

        if ($expected instanceof \Closure) {
            PHPUnit::assertTrue(
                $expected(is_array($actual) ? Collection::make($actual) : $actual),
                sprintf('Inertia property [%s] was marked as invalid using a closure.', $this->dotPath($key))
            );

            return $this;
        }

        if ($expected instanceof Arrayable) {
            $expected = $expected->toArray();
        } elseif ($expected instanceof JsonResource) {
            $expected = json_decode(
                json_encode($expected->toResponse()->getBody()->getContents(), JSON_THROW_ON_ERROR),
                true,
                512,
                JSON_THROW_ON_ERROR
            );
        }

        $this->ensureSorted($expected);
        $this->ensureSorted($actual);

        PHPUnit::assertSame(
            $expected,
            $actual,
            sprintf('Inertia property [%s] does not match the expected value.', $this->dotPath($key))
        );

        return $this;
    }

    abstract public function has(string $key, $value = null, ?\Closure $scope = null);

    protected function ensureSorted(&$value): void
    {
        if (! is_array($value)) {
            return;
        }

        foreach ($value as &$arg) {
            $this->ensureSorted($arg);
        }

        ksort($value);
    }

    abstract protected function dotPath(string $key): string;

    abstract protected function prop(?string $key = null);
}
