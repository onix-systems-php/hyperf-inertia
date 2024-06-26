<?php

declare(strict_types=1);
/**
 * This file is part of the Inertia library for Hyperf.
 *
 * @license  https://github.com/onix-systems-php/hyperf-inertia/blob/main/LICENSE
 */

namespace OnixSystemsPHP\HyperfInertia\Ssr;

class Response
{
    public string $head;

    public string $body;

    /**
     * Prepare the Inertia Server Side Rendering (SSR) response.
     */
    public function __construct(string $head, string $body)
    {
        $this->head = $head;
        $this->body = $body;
    }
}
