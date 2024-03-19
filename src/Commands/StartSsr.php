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
use OnixSystemsPHP\HyperfInertia\Ssr\BundleDetector;
use OnixSystemsPHP\HyperfInertia\Ssr\SsrException;
use Symfony\Component\Process\Process;
use function Hyperf\Config\config;

#[Command]
class StartSsr extends HyperfCommand
{
    protected ?string $signature = 'inertia:start-ssr {--runtime=node : The runtime to use (`node` or `bun`)}';

    protected string $description = 'Start the Inertia SSR server';

    /**
     * Start the SSR server via a Node process.
     *
     * @throws SsrException
     */
    public function handle(): int
    {
        if (! config('inertia.ssr.enabled', true)) {
            $this->error('Inertia SSR is not enabled. Enable it via the `inertia.ssr.enabled` config option.');

            return self::FAILURE;
        }

        $bundle = (new BundleDetector())->detect();
        $configuredBundle = config('inertia.ssr.bundle');

        if ($bundle === null) {
            $this->error(
                $configuredBundle
                    ? 'Inertia SSR bundle not found at the configured path: "' . $configuredBundle . '"'
                    : 'Inertia SSR bundle not found. Set the correct Inertia SSR bundle path in your `inertia.ssr.bundle` config.'
            );

            return self::FAILURE;
        }
        if ($configuredBundle && $bundle !== $configuredBundle) {
            $this->warn('Inertia SSR bundle not found at the configured path: "' . $configuredBundle . '"');
            $this->warn('Using a default bundle instead: "' . $bundle . '"');
        }

        $runtime = $this->option('runtime');

        if (! in_array($runtime, ['node', 'bun'])) {
            $this->error('Unsupported runtime: "' . $runtime . '". Supported runtimes are `node` and `bun`.');

            return self::INVALID;
        }

        $this->call('inertia:stop-ssr');

        $process = new Process([$runtime, $bundle]);
        $process->setTimeout(null);
        $process->start();

        if (extension_loaded('pcntl')) {
            $stop = static fn () => $process->stop();
            pcntl_async_signals(true);
            pcntl_signal(SIGINT, $stop);
            pcntl_signal(SIGQUIT, $stop);
            pcntl_signal(SIGTERM, $stop);
        }

        foreach ($process as $type => $data) {
            if ($process::OUT === $type) {
                $this->info(trim($data));
            } else {
                $this->error(trim($data));
                throw new SsrException($data);
            }
        }

        return self::SUCCESS;
    }
}
