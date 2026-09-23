<?php

namespace App\Providers;

use Illuminate\Foundation\Http\Events\RequestHandled;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $appUrl = rtrim((string) config('app.url'), '/');

        if ($appUrl !== '') {
            URL::forceRootUrl($appUrl);

            if (str_starts_with($appUrl, 'https://')) {
                URL::forceScheme('https');
            }
        }

        $path = rtrim((string) parse_url($appUrl, PHP_URL_PATH), '/');

        if ($path !== '' && $path !== '/') {
            $script = config('app.debug') ? 'livewire.js' : 'livewire.min.js';
            config([
                'livewire.asset_url' => $appUrl.'/livewire/'.$script,
            ]);

            // Livewire injects script tags after the middleware stack; fix URLs then.
            Event::listen(RequestHandled::class, function (RequestHandled $event) use ($path) {
                $response = $event->response;

                if (! method_exists($response, 'getContent')) {
                    return;
                }

                $content = $response->getContent();

                if (! is_string($content) || ! str_contains($content, '/livewire/')) {
                    return;
                }

                $updated = str_replace(
                    [
                        'src="/livewire/',
                        'data-update-uri="/livewire/',
                        "src='/livewire/",
                        "data-update-uri='/livewire/",
                    ],
                    [
                        'src="'.$path.'/livewire/',
                        'data-update-uri="'.$path.'/livewire/',
                        "src='".$path.'/livewire/',
                        "data-update-uri='".$path.'/livewire/',
                    ],
                    $content
                );

                if ($updated !== $content) {
                    $response->setContent($updated);
                }
            });
        }
    }
}
