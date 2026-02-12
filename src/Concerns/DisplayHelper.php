<?php

namespace Outhebox\Translations\Concerns;

use function Laravel\Prompts\note;

trait DisplayHelper
{
    private string $fg = '';

    private string $shadow = '';

    private string $bg = '';

    private function displayHeader(string $subtitle): void
    {
        $this->initTheme();
        $this->displayLogo();
        $this->displayTagline($subtitle);
    }

    private function initTheme(): void
    {
        $palettes = [
            ['fg' => "\e[38;5;75m", 'shadow' => "\e[38;5;25m", 'bg' => "\e[48;5;25m"],
            ['fg' => "\e[38;5;114m", 'shadow' => "\e[38;5;22m", 'bg' => "\e[48;5;22m"],
            ['fg' => "\e[38;5;178m", 'shadow' => "\e[38;5;94m", 'bg' => "\e[48;5;94m"],
            ['fg' => "\e[38;5;204m", 'shadow' => "\e[38;5;89m", 'bg' => "\e[48;5;89m"],
            ['fg' => "\e[38;5;141m", 'shadow' => "\e[38;5;55m", 'bg' => "\e[48;5;55m"],
            ['fg' => "\e[38;5;80m", 'shadow' => "\e[38;5;30m", 'bg' => "\e[48;5;30m"],
            ['fg' => "\e[38;5;252m", 'shadow' => "\e[38;5;238m", 'bg' => "\e[48;5;238m"],
            ['fg' => "\e[38;5;215m", 'shadow' => "\e[38;5;130m", 'bg' => "\e[48;5;130m"],
        ];

        $palette = $palettes[array_rand($palettes)];
        $this->fg = $palette['fg'];
        $this->shadow = $palette['shadow'];
        $this->bg = $palette['bg'];
    }

    private function displayLogo(): void
    {
        $left = [
            '                        ',
            '▀█▀ █▀▀█ █▀▀█ █▀▀█ █▀▀▀',
            ' █  █__^ █__█ █  █ ▀▀▀█',
            ' ▀  ▀  ▀ ▀  ▀ ▀  ▀ ▀▀▀▀',
        ];

        $right = [
            '      ',
            '█  █ █',
            '█__█ █',
            '▀▀▀▀ ▀',
        ];

        $reset = "\e[0m";
        $this->newLine();

        foreach ($left as $index => $line) {
            $rendered = '  '.$this->renderGlyphs($line);
            $rendered .= '  '.$this->renderGlyphs($right[$index]);
            $this->output->writeln($rendered.$reset);
        }

        $this->newLine();
    }

    private function renderGlyphs(string $line): string
    {
        $reset = "\e[0m";
        $parts = [];

        $chars = mb_str_split($line);

        foreach ($chars as $char) {
            $parts[] = match ($char) {
                '_' => $this->bg.' '.$reset,
                '^' => $this->fg.$this->bg.'▀'.$reset,
                '~' => $this->shadow.'▀'.$reset,
                ' ' => ' ',
                default => $this->fg.$char.$reset,
            };
        }

        return implode('', $parts);
    }

    private function displayTagline(string $subtitle): void
    {
        $badge = "\e[48;5;238m\e[97m\e[1m {$subtitle} \e[0m";

        note("  {$badge}");
    }
}
