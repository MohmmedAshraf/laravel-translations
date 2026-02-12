<?php

use Outhebox\Translations\Concerns\DisplayHelper;

it('renders all glyph types including ^ and ~', function () {
    $command = new class extends Illuminate\Console\Command
    {
        use DisplayHelper;

        protected $name = 'test:display';

        public function testRenderGlyphs(string $line): string
        {
            $this->initTheme();

            $method = new ReflectionMethod($this, 'renderGlyphs');

            return $method->invoke($this, $line);
        }

        public function testInitTheme(): void
        {
            $method = new ReflectionMethod($this, 'initTheme');
            $method->invoke($this);
        }
    };

    $command->testInitTheme();

    $result = $command->testRenderGlyphs('█ _^~ ');

    expect($result)->toBeString();
    expect(strlen($result))->toBeGreaterThan(0);
});

it('displays header without errors', function () {
    $this->artisan('translations:import', ['--no-interaction' => true, '--help' => true])
        ->assertSuccessful();
});
