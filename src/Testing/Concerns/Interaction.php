<?php

declare(strict_types=1);
/**
 * This file is part of the extension library for Hyperf.
 *
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace OnixSystemsPHP\HyperfInertia\Testing\Concerns;

use Hyperf\Stringable\Str;
use PHPUnit\Framework\Assert as PHPUnit;

trait Interaction
{
    protected array $interacted = [];

    public function interacted(): void
    {
        PHPUnit::assertSame(
            [],
            array_diff(array_keys($this->prop()), $this->interacted),
            $this->path
                ? sprintf('Unexpected Inertia properties were found in scope [%s].', $this->path)
                : 'Unexpected Inertia properties were found on the root level.'
        );
    }

    public function etc(): self
    {
        $this->interacted = array_keys($this->prop());

        return $this;
    }

    protected function interactsWith(string $key): void
    {
        $prop = Str::before($key, '.');

        if (! in_array($prop, $this->interacted, true)) {
            $this->interacted[] = $prop;
        }
    }

    abstract protected function prop(string $key = null);
}
