<?php

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\HtmlString;
use Outhebox\TranslationsUI\Models\Contributor;

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

        $file = asset("/vendor/translations-ui/{$manifest['resources/scripts/app.ts']['file']}");
        $css = asset("/vendor/translations-ui/{$manifest['resources/scripts/app.ts']['css'][0]}");

        return new HtmlString(<<<HTML
                <script type="module" src="{$file}"></script>
                <link rel="stylesheet" href="{$css}">
            HTML
        );
    }
}

if (! function_exists('getPhraseParameters')) {
    function getPhraseParameters(string $phrase): ?array
    {
        preg_match_all('/(?<!\w):(\w+)/', $phrase, $matches);

        if (empty($matches[1])) {
            return null;
        }

        return $matches[1];
    }
}

if (! function_exists('buildPhrasesTree')) {
    function buildPhrasesTree($phrases, $locale): array
    {
        $tree = [];

        /** @var \Outhebox\TranslationsUI\Models\Phrase $phrase */
        foreach ($phrases as $phrase) {
            if ($phrase->file->file_name === "$locale.json") {
                $tree[$locale][$phrase->file->file_name][$phrase->key] = ! blank($phrase->value) ? $phrase->value : $phrase->source->value;

                continue;
            }
            setArrayValue(
                array: $tree[$locale][$phrase->file->file_name],
                key: $phrase->key,
                value: ! blank($phrase->value) ? $phrase->value : $phrase->source->value
            );
        }

        return $tree;
    }
}

if (! function_exists('setArrayValue')) {
    function setArrayValue(&$array, $key, $value)
    {
        if (is_null($key)) {
            return $array = $value;
        }

        $keys = preg_split('/\.(?=[^.]*[^.])/', $key);

        foreach ($keys as $i => $key) {
            if (blank($value)) {
                dd($key, $value);
            }

            if (count($keys) === 1) {
                break;
            }

            unset($keys[$i]);

            if (! isset($array[$key]) || ! is_array($array[$key])) {
                $array[$key] = [];
            }

            $array = &$array[$key];
        }

        $lastKey = array_shift($keys);

        if (! blank($lastKey)) {
            $array[$lastKey] = $value;
        }

        return $array;
    }
}

if (! function_exists('currentUser')) {
    function currentUser(): null|Authenticatable|Contributor
    {
        return auth()->user();
    }
}
