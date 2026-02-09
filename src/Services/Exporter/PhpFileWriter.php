<?php

namespace Outhebox\Translations\Services\Exporter;

class PhpFileWriter
{
    public function write(string $filePath, array $translations, bool $sortKeys = true): bool
    {
        $unflattened = $this->unflatten($translations);

        if ($sortKeys) {
            $this->recursiveSort($unflattened);
        }

        $content = "<?php\n\nreturn ".$this->exportArray($unflattened).";\n";

        $this->ensureDirectory(dirname($filePath));

        return file_put_contents($filePath, $content) !== false;
    }

    public function unflatten(array $dotted): array
    {
        $result = [];

        foreach ($dotted as $key => $value) {
            data_set($result, $key, $value);
        }

        return $result;
    }

    private function exportArray(array $array, int $indent = 1): string
    {
        if (empty($array)) {
            return '[]';
        }

        $pad = str_repeat('    ', $indent);
        $closePad = str_repeat('    ', $indent - 1);
        $lines = [];

        foreach ($array as $key => $value) {
            $exportedKey = var_export($key, true);
            $exportedValue = is_array($value)
                ? $this->exportArray($value, $indent + 1)
                : var_export($value, true);

            $lines[] = $pad.$exportedKey.' => '.$exportedValue.',';
        }

        return "[\n".implode("\n", $lines)."\n".$closePad.']';
    }

    private function ensureDirectory(string $dir): void
    {
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
    }

    private function recursiveSort(array &$array): void
    {
        ksort($array);

        foreach ($array as &$value) {
            if (is_array($value)) {
                $this->recursiveSort($value);
            }
        }
    }
}
