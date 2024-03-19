<?php

declare(strict_types=1);
/**
 * This file is part of the extension library for Hyperf.
 *
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace OnixSystemsPHP\HyperfInertia\Ssr;

use function Hyperf\Collection\collect;
use function Hyperf\Config\config;

class BundleDetector
{
    public function detect()
    {
        return collect([
            config('inertia.ssr.bundle'),
            BASE_PATH . '/bootstrap/ssr/ssr.mjs',
            BASE_PATH . '/bootstrap/ssr/ssr.js',
            BASE_PATH . '/storage/public/assets/js/ssr.js',
        ])->filter()->first(fn ($path) => file_exists($path));
    }
}
