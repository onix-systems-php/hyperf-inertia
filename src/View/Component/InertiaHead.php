<?php

declare(strict_types=1);
/**
 * This file is part of the Inertia library for Hyperf.
 *
 * @license  https://github.com/onix-systems-php/hyperf-inertia/blob/main/LICENSE
 */

namespace OnixSystemsPHP\HyperfInertia\View\Component;

use Hyperf\ViewEngine\Component\Component;
use OnixSystemsPHP\HyperfInertia\Vite;

class InertiaHead extends Component
{
    public $page = [];

    public array $resource = [];

    public function __construct($page, array $resource)
    {
        $this->page = $page;
        $this->resource = $resource;
    }

    public function render(): mixed
    {
        echo \Hyperf\Support\make(Vite::class)($this->resource);

        $template = '<?php
            if (!isset($__inertiaSsrDispatched)) {
                $__inertiaSsrDispatched = true;
                $__inertiaSsrResponse = \Hyperf\Support\make(\OnixSystemsPHP\HyperfInertia\Ssr\HttpGateway::class)
                    ->dispatch($page);
            }

            if ($__inertiaSsrResponse) {
                echo $__inertiaSsrResponse->head;
            }
        ?>';

        return implode(' ', array_map('trim', explode("\n", $template)));
    }
}
