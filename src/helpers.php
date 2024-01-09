<?php

use Illuminate\Support\HtmlString;

if (! function_exists('translationsUIAssets')) {
    function translationsUIAssets(): HtmlString
    {
        $hot = __DIR__.'/../resources/dist/hot';

        $devServerIsRunning = file_exists($hot);

        if ($devServerIsRunning) {
            $viteServer = file_get_contents($hot);

            return new HtmlString(<<<HTML
                <script type="module" src="$viteServer/@vite/client"></script>
                <script type="module" src="$viteServer/resources/scripts/app.ts"></script>
            HTML
            );
        }

        $manifestPath = public_path('vendor/translations-ui/manifest.json');

        if (! file_exists($manifestPath)) {
            return new HtmlString(<<<'HTML'
                <div>The manifest.json file could not be found.</div>
            HTML
            );
        }

        $manifest = json_decode(file_get_contents($manifestPath), true);

        return new HtmlString(<<<HTML
                <script type="module" src="/vendor/translations-ui/{$manifest['resources/scripts/app.ts']['file']}"></script>
                <link rel="stylesheet" href="/vendor/translations-ui/{$manifest['resources/scripts/app.ts']['css'][0]}">
            HTML
        );
    }
}
