<?php

declare(strict_types=1);
/**
 * This file is part of the Inertia library for Hyperf.
 *
 * @license  https://github.com/onix-systems-php/hyperf-inertia/blob/main/LICENSE
 */

namespace OnixSystemsPHP\HyperfInertia\View\Component;

use Hyperf\ViewEngine\Component\Component;

class ReactRefresh extends Component
{
    public function render(): mixed
    {
        return '<?php echo \\Hyperf\\Support\\make(\\OnixSystemsPHP\\HyperfInertia\\Vite::class)->reactRefresh() ?>';
    }
}
