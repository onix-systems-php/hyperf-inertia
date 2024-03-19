<?php

declare(strict_types=1);
/**
 * This file is part of the extension library for Hyperf.
 *
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace OnixSystemsPHP\HyperfInertia\Ssr;

use GuzzleHttp\Client;
use Hyperf\Context\ApplicationContext;

use function Hyperf\Config\config;

class HttpGateway implements Gateway
{
    /**
     * Dispatch the Inertia page to the Server Side Rendering engine.
     */
    public function dispatch(array $page): ?Response
    {
        if (! config('inertia.ssr.enabled', true) || ! (new BundleDetector())->detect()) {
            return null;
        }

        $url = str_replace('/render', '', config('inertia.ssr.url', 'http://127.0.0.1:13714')) . '/render';

        try {
            $client = ApplicationContext::getContainer()->get(Client::class);

            $response = json_decode(
                $client->post($url, ['json' => $page])->getBody()->getContents(),
                true,
                512,
                JSON_THROW_ON_ERROR
            );
        } catch (\Exception $e) {
            return null;
        }

        if (is_null($response)) {
            return null;
        }

        return new Response(
            implode("\n", $response['head']),
            $response['body']
        );
    }
}
