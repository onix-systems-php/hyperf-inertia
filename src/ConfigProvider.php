<?php

declare(strict_types=1);
/**
 * This file is part of the extension library for Hyperf.
 *
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace OnixSystemsPHP\HyperfInertia;

use OnixSystemsPHP\HyperfInertia\Commands\InertiaInitCommand;
use OnixSystemsPHP\HyperfInertia\Commands\StartSsr;
use OnixSystemsPHP\HyperfInertia\Commands\StopSsr;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => [
            ],
            'commands' => [
                StartSsr::class,
                StopSsr::class,
                InertiaInitCommand::class,
            ],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],
            'listeners' => [
            ],

            'publish' => [
                [
                    'id' => 'config',
                    'description' => 'The config for onix-systems-php/hyperf-inertia.',
                    'source' => __DIR__ . '/../publish/inertia.php',
                    'destination' => BASE_PATH . '/config/autoload/inertia.php',
                ],
                [
                    'id' => 'middleware',
                    'description' => 'The middleware for onix-systems-php/hyperf-inertia.',
                    'source' => __DIR__ . '/../publish/HandleInertiaMiddleware.php',
                    'destination' => BASE_PATH . '/app/Common/Middleware/HandleInertiaMiddleware.php',
                ],
            ],

            'view' => [
                'components' => [
                    'inertia-body' => \OnixSystemsPHP\HyperfInertia\View\Component\InertiaBody::class,
                    'inertia-head' => \OnixSystemsPHP\HyperfInertia\View\Component\InertiaHead::class,
                    'react-refresh' => \OnixSystemsPHP\HyperfInertia\View\Component\ReactRefresh::class,
                ],
            ],
        ];
    }
}
