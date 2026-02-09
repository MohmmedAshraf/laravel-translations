<?php

namespace Outhebox\Translations\Services\Importer;

class ParameterExtractor
{
    public function extract(string $text): array
    {
        preg_match_all('/(?<!\w):([a-zA-Z_][a-zA-Z0-9_]*)/', $text, $colonMatches);
        preg_match_all('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', $text, $braceMatches);

        $parameters = array_merge($colonMatches[0], $braceMatches[0]);

        return array_values(array_unique($parameters));
    }

    public function containsHtml(string $text): bool
    {
        return (bool) preg_match('/<[a-zA-Z][a-zA-Z0-9]*(\s[^>]*)?\/?>/s', $text);
    }

    public function isPlural(string $text): bool
    {
        return str_contains($text, '|') && ! preg_match('/ \| /', $text);
    }
}
