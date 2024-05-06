<?php

declare(strict_types=1);
/**
 * This file is part of the Inertia library for Hyperf.
 *
 * @license  https://github.com/onix-systems-php/hyperf-inertia/blob/main/LICENSE
 */

namespace OnixSystemsPHP\HyperfInertia\Commands;

use Hyperf\Command\Annotation\Command;
use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Stringable\Str;
use Hyperf\Support\Filesystem\Filesystem;
use OnixSystemsPHP\HyperfInertia\Enum\StackEnum;
use Symfony\Component\Console\Question\ChoiceQuestion;

#[Command]
class InertiaInitCommand extends HyperfCommand
{
    public const INERTIA_PATH = '/storage/inertia';

    protected array $nodePackages = [
        'core' => [
            'autoprefixer' => '*',
        ],
        'vue' => [
            'default' => [
                'vue' => '^3.*',
                '@inertiajs/vue3' => '^1.*',
                '@vitejs/plugin-vue' => '^4.*',
                '@vue/compiler-sfc' => '^3.*',
                'vite' => '^4.*',
                'laravel-vite-plugin' => '^0.*',
            ],
            'ssr' => ['@vue/server-renderer' => '^3.*'],
        ],
        'react' => [
            'default' => [
                '@inertiajs/inertia-react' => '^0.*',
                '@inertiajs/react' => '^1.*',
                '@vitejs/plugin-react' => '^4.*',
                'react' => '^18.*',
                'react-dom' => '^18.*',
                'vite' => '^4.*',
                'laravel-vite-plugin' => '^0.*',
            ],
            'ssr' => [],
        ],
        'svelte' => [
            'default' => [
                'svelte' => '^4.*',
                '@inertiajs/svelte' => '^1.*',
                '@sveltejs/vite-plugin-svelte' => '^3.*',
                '@vue/compiler-sfc' => '^3.*',
                'laravel-vite-plugin' => '^1.0.2',
            ],
            'ssr' => [],
        ],
    ];

    private bool $hasSsr = false;

    public function __construct(private readonly Filesystem $filesystem)
    {
        parent::__construct('inertia:init');
    }

    public function configure(): void
    {
        parent::configure();
        $this->setDescription('Install the Inertia components and resources');
    }

    public function handle(): void
    {
        $this->makeStructure();
        $stackQuestion = new ChoiceQuestion('Select the development stack', array_column(StackEnum::cases(), 'value'));
        $stack = $this->getHelper('question')->ask($this->input, $this->output, $stackQuestion);

        $ssrQuestion = new ChoiceQuestion('Do you want to enable server side rendering?', ['yes', 'no'], 1);
        $hasSrr = $this->getHelper('question')->ask($this->input, $this->output, $ssrQuestion);
        $this->hasSsr = $hasSrr === 'yes';

        $stack = StackEnum::tryFrom($stack);
        $path = $this->getStubPath($stack);
        $resources = $this->getResources($stack);
        $this->copyResources($path, $resources);

        $nodePackages = $this->getNodePackages($stack);
        $this->updateNodePackages(function ($packages) use ($nodePackages) {
            return [...$packages, ...$nodePackages];
        });
    }

    protected function updateNodePackages(callable $callback, $dev = true): void
    {
        if (! $this->filesystem->exists(BASE_PATH . '/package.json')) {
            $this->error('The package.json file was not found in the root directory. Initialize the file and try again');
            return;
        }

        $configurationKey = $dev ? 'devDependencies' : 'dependencies';

        $packages = json_decode(file_get_contents(BASE_PATH . '/package.json'), true);

        $packages[$configurationKey] = $callback(
            array_key_exists($configurationKey, $packages) ? $packages[$configurationKey] : [],
            $configurationKey
        );

        ksort($packages[$configurationKey]);

        file_put_contents(
            BASE_PATH . '/package.json',
            json_encode($packages, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) . PHP_EOL
        );
    }

    private function makeStructure(): void
    {
        $this->filesystem->makeDirectory(path: self::getInertiaPath() . '/js/Components', recursive: true);
        $this->filesystem->makeDirectory(path: self::getInertiaPath() . '/js/Pages', recursive: true);
        $this->filesystem->makeDirectory(path: self::getInertiaPath() . '/js/Layouts', recursive: true);
        $this->filesystem->makeDirectory(path: self::getInertiaPath() . '/css', recursive: true);
    }

    private function getResources(StackEnum $stack): array
    {
        $resources = [
            'vite' => 'vite.config.js',
            'app' => 'inertia.blade.php',
            'css' => 'app.css',
            'bootstrap' => 'bootstrap.js',
        ];

        $additional = match ($stack) {
            StackEnum::VUE => ['index' => 'app.js', 'ssr' => 'ssr.js'],
            StackEnum::REACT => ['index' => 'app.jsx', 'ssr' => 'ssr.jsx'],
            StackEnum::SVELTE => ['index' => 'app.js', 'ssr' => 'ssr.js'],
        };

        return [...$resources, ...$additional];
    }

    private function getNodePackages(StackEnum $stack): array
    {
        $nodePackages = [
            ...$this->nodePackages['core'],
            ...$this->nodePackages[$stack->value]['default'],
        ];

        if ($this->hasSsr) {
            $nodePackages = [...$nodePackages, ...$this->nodePackages[$stack->value]['ssr']];
        }

        return $nodePackages;
    }

    private function getStubPath(StackEnum $stack): string
    {
        return __DIR__ . '/Stubs/' . Str::ucfirst($stack->value) . '/';
    }

    private function copyResources(string $path, array $resources): void
    {
        $this->copyResource($path, BASE_PATH . '/storage/view/layouts/', $resources['app']);
        $this->copyResource($path, BASE_PATH . '/', $resources['vite']);
        $this->copyResource($path, self::getInertiaPath() . '/js/', $resources['bootstrap']);
        $this->copyResource($path, self::getInertiaPath() . '/js/', $resources['index']);
        $this->copyResource($path, self::getInertiaPath() . '/css/', $resources['css']);

        if ($this->hasSsr) {
            $this->copyResource($path, self::getInertiaPath() . '/js/', $resources['ssr']);
        }
    }

    private function copyResource(string $path, string $target, string $fileName): void
    {
        if (! $this->filesystem->exists($target)) {
            $this->filesystem->makeDirectory($target);
        }

        if ($this->filesystem->copy($path . $fileName, $target . $fileName)) {
            $this->info(sprintf('%s Was copied', $target . $fileName));
        }
    }

    private static function getInertiaPath(): string
    {
        return BASE_PATH . self::INERTIA_PATH;
    }
}
