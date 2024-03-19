<?php

declare(strict_types=1);
/**
 * This file is part of the extension library for Hyperf.
 *
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace OnixSystemsPHP\HyperfInertia\Commands;

use Hyperf\Command\Annotation\Command;
use Hyperf\Command\Command as HyperfCommand;

use function Hyperf\Config\config;

#[Command]
class StopSsr extends HyperfCommand
{
    protected ?string $name = 'inertia:stop-ssr';

    protected string $description = 'Stop the Inertia SSR server';

    /**
     * Stop the SSR server.
     */
    public function handle(): int
    {
        $url = str_replace('/render', '', config('inertia.ssr.url', 'http://127.0.0.1:13714')) . '/shutdown';

        $ch = curl_init($url);
        curl_exec($ch);

        if (curl_error($ch) !== 'Empty reply from server') {
            $this->error('Unable to connect to Inertia SSR server.');

            return self::FAILURE;
        }

        $this->info('Inertia SSR server stopped.');

        curl_close($ch);

        return self::SUCCESS;
    }
}
