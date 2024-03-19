<?php

namespace OnixSystemsPHP\HyperfInertia\View\Component;

use Hyperf\ViewEngine\Component\Component;

class InertiaHead extends Component
{
    public $page = [];

    public array $resource = [];

    public function __construct($page, array $resource) {
        $this->page = $page;
        $this->resource = $resource;
    }
    public function render(): mixed
    {
        echo \Hyperf\Support\make(\OnixSystemsPHP\HyperfInertia\Vite::class)($this->resource);

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
