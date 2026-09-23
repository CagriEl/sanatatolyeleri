<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FixLivewireSubdirectoryUrls
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $prefix = rtrim((string) parse_url((string) config('app.url'), PHP_URL_PATH), '/');

        if ($prefix === '' || ! method_exists($response, 'getContent')) {
            return $response;
        }

        $content = $response->getContent();

        if (! is_string($content) || $content === '') {
            return $response;
        }

        $replacements = [
            'src="/livewire/' => 'src="'.$prefix.'/livewire/',
            'data-update-uri="/livewire/' => 'data-update-uri="'.$prefix.'/livewire/',
            "src='/livewire/" => "src='".$prefix.'/livewire/',
            "data-update-uri='/livewire/" => "data-update-uri='".$prefix.'/livewire/',
            'data-update-uri="/livewire/update"' => 'data-update-uri="'.$prefix.'/livewire/update"',
        ];

        $updated = str_replace(array_keys($replacements), array_values($replacements), $content);

        // Absolute fallback: any remaining root-relative livewire update uri
        $updated = preg_replace(
            '#data-update-uri="/livewire/update"#',
            'data-update-uri="'.$prefix.'/livewire/update"',
            $updated
        ) ?? $updated;

        if ($updated !== $content) {
            $response->setContent($updated);
        }

        return $response;
    }
}
