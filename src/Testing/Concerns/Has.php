<?php

declare(strict_types=1);
/**
 * This file is part of the extension library for Hyperf.
 *
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace OnixSystemsPHP\HyperfInertia\Testing\Concerns;

use Hyperf\Collection\Arr;
use Hyperf\Collection\Collection;
use PHPUnit\Framework\Assert as PHPUnit;

trait Has
{
    public function hasAll($key): self
    {
        $keys = is_array($key) ? $key : func_get_args();

        foreach ($keys as $prop => $count) {
            if (is_int($prop)) {
                $this->has($count);
            } else {
                $this->has($prop, $count);
            }
        }

        return $this;
    }

    /**
     * @param mixed $value
     *
     * @return $this
     */
    public function has(string $key, $value = null, \Closure $scope = null): self
    {
        PHPUnit::assertTrue(
            Arr::has($this->prop(), $key),
            sprintf('Inertia property [%s] does not exist.', $this->dotPath($key))
        );

        $this->interactsWith($key);

        if (is_int($value) && ! is_null($scope)) {
            $path = $this->dotPath($key);

            $prop = $this->prop($key);
            if ($prop instanceof Collection) {
                $prop = $prop->all();
            }

            PHPUnit::assertTrue($value > 0, sprintf('Cannot scope directly onto the first entry of property [%s] when asserting that it has a size of 0.', $path));
            PHPUnit::assertIsArray($prop, sprintf('Direct scoping is currently unsupported for non-array like properties such as [%s].', $path));
            $this->count($key, $value);

            return $this->scope($key . '.' . array_keys($prop)[0], $scope);
        }

        if (is_callable($value)) {
            $this->scope($key, $value);
        } elseif (! is_null($value)) {
            $this->count($key, $value);
        }

        return $this;
    }

    public function missingAll($key): self
    {
        $keys = is_array($key) ? $key : func_get_args();

        foreach ($keys as $prop) {
            $this->misses($prop);
        }

        return $this;
    }

    public function missing(string $key): self
    {
        $this->interactsWith($key);

        PHPUnit::assertNotTrue(
            Arr::has($this->prop(), $key),
            sprintf('Inertia property [%s] was found while it was expected to be missing.', $this->dotPath($key))
        );

        return $this;
    }

    public function missesAll($key): self
    {
        return $this->missingAll(
            is_array($key) ? $key : func_get_args()
        );
    }

    public function misses(string $key): self
    {
        return $this->missing($key);
    }

    protected function count(string $key, int $length): self
    {
        PHPUnit::assertCount(
            $length,
            $this->prop($key),
            sprintf('Inertia property [%s] does not have the expected size.', $this->dotPath($key))
        );

        return $this;
    }

    abstract protected function prop(string $key = null);

    abstract protected function dotPath(string $key): string;

    abstract protected function interactsWith(string $key): void;

    abstract protected function scope(string $key, \Closure $callback);
}
