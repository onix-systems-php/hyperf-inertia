<?php

declare(strict_types=1);
/**
 * This file is part of the Inertia library for Hyperf.
 *
 * @license  https://github.com/onix-systems-php/hyperf-inertia/blob/main/LICENSE
 */

namespace OnixSystemsPHP\HyperfInertia\View\Component;

use Hyperf\ViewEngine\Component\Component;

class InertiaBody extends Component
{
    public $page;

    public $expression;

    public function __construct(array $page = [], string $expression = '')
    {
        $this->page = $page;
        $this->expression = $expression;
    }

    public function render(): mixed
    {
        $id = trim(trim($this->expression), "\\'\"") ?: 'app';

        $template = '<?php
            if (!isset($__inertiaSsrDispatched)) {
                $__inertiaSsrDispatched = true;
                $__inertiaSsrResponse = \Hyperf\Support\make(\OnixSystemsPHP\HyperfInertia\Ssr\HttpGateway::class)
                    ->dispatch($page);
            }

            if ($__inertiaSsrResponse) {
                echo $__inertiaSsrResponse->body;
            } else {
                ?><div id="' . $id . '" data-page="{{ json_encode($page) }}"></div><?php
            }?>';

        return implode(' ', array_map('trim', explode("\n", $template)));
    }
}
