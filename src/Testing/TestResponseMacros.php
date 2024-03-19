<?php

declare(strict_types=1);
/**
 * This file is part of the extension library for Hyperf.
 *
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace OnixSystemsPHP\HyperfInertia\Testing;

use Hyperf\Testing\Fluent\AssertableJson;
use Hyperf\Testing\Http\TestResponse;

class TestResponseMacros extends TestResponse
{
    public function assertInertia()
    {
        return function (\Closure $callback = null) {
            if (class_exists(AssertableJson::class)) {
                $assert = AssertableInertia::fromTestResponse($this);
            } else {
                $assert = Assert::fromTestResponse($this);
            }

            if (is_null($callback)) {
                return $this;
            }

            $callback($assert);

            return $this;
        };
    }

    public function inertiaPage()
    {
        return function () {
            if (class_exists(AssertableJson::class)) {
                return AssertableInertia::fromTestResponse($this)->toArray();
            }

            return Assert::fromTestResponse($this)->toArray();
        };
    }
}
