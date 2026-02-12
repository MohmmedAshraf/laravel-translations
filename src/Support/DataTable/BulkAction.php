<?php

declare(strict_types=1);

namespace Outhebox\Translations\Support\DataTable;

use Closure;

class BulkAction
{
    private string $name;

    private ?string $label = null;

    private ?string $icon = null;

    private ?string $url = null;

    private ?Closure $handler = null;

    private ?string $confirm = null;

    private ?string $confirmTitle = null;

    private string $variant = 'default';

    private bool $download = false;

    private function __construct(string $name)
    {
        $this->name = $name;
    }

    public static function make(string $name): self
    {
        return new self($name);
    }

    public function label(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function icon(string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function action(string|Closure $action): self
    {
        if ($action instanceof Closure) {
            $this->handler = $action;
        } elseif (str_starts_with($action, '/') || str_starts_with($action, 'http')) {
            $this->url = $action;
        } else {
            $this->url = route($action);
        }

        return $this;
    }

    public function confirm(?string $message = null, ?string $title = null): self
    {
        $this->confirm = $message ?? 'Are you sure you want to perform this action?';
        $this->confirmTitle = $title;

        return $this;
    }

    public function destructive(): self
    {
        $this->variant = 'destructive';

        return $this;
    }

    public function download(): self
    {
        $this->download = true;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getHandler(): ?Closure
    {
        return $this->handler;
    }

    public function hasHandler(): bool
    {
        return $this->handler !== null;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'label' => $this->label ?? str($this->name)->headline()->toString(),
            'icon' => $this->icon,
            'url' => $this->url,
            'confirm' => $this->confirm,
            'confirmTitle' => $this->confirmTitle ?? 'Confirm Action',
            'variant' => $this->variant,
            'download' => $this->download,
        ];
    }
}
