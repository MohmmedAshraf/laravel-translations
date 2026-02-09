<?php

declare(strict_types=1);

namespace Outhebox\Translations\Support\DataTable;

use BackedEnum;
use Closure;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Outhebox\Translations\Contracts\HasColor;
use Outhebox\Translations\Contracts\HasIcon;
use Outhebox\Translations\Contracts\HasLabel;

class Filter
{
    private string $key;

    private ?string $label = null;

    private ?string $icon = null;

    private bool $searchable = false;

    private array $options = [];

    private function __construct(string $key, ?string $enum = null)
    {
        $this->key = $key;

        if ($enum && is_subclass_of($enum, BackedEnum::class)) {
            $this->options = $this->buildOptionsFromEnum($enum);
        }
    }

    public static function make(string $key, ?string $enum = null): self
    {
        return new self($key, $enum);
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

    public function searchable(): self
    {
        $this->searchable = true;

        return $this;
    }

    public function options(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    public function fromModel(Builder|string $query, string|Closure $labelKey = 'name', string $valueKey = 'id'): self
    {
        $builder = is_string($query) ? $query::query() : $query;

        $this->options = $builder->get()->map(function (Model $model) use ($labelKey, $valueKey) {
            return [
                'value' => (string) $model->getAttribute($valueKey),
                'label' => $labelKey instanceof Closure ? $labelKey($model) : $model->getAttribute($labelKey),
            ];
        })->all();

        return $this;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function toArray(): array
    {
        return [
            'key' => $this->key,
            'label' => $this->label ?? str($this->key)->headline()->toString(),
            'icon' => $this->icon,
            'options' => $this->options,
            'searchable' => $this->searchable,
        ];
    }

    private function buildOptionsFromEnum(string $enumClass): array
    {
        $options = [];

        foreach ($enumClass::cases() as $case) {
            $option = [
                'value' => $case->value,
                'label' => $case instanceof HasLabel ? $case->getLabel() : $case->name,
            ];

            if ($case instanceof HasIcon && $icon = $case->getIcon()) {
                $option['icon'] = $icon;
            }

            if ($case instanceof HasColor) {
                $option['iconColor'] = $this->colorToIconColor($case->getColor());
            }

            $options[] = $option;
        }

        return $options;
    }

    private function colorToIconColor(string $color): string
    {
        return match ($color) {
            'green', 'default' => 'green',
            'blue', 'secondary' => 'blue',
            'red', 'destructive' => 'red',
            'amber', 'warning', 'outline' => 'amber',
            default => 'neutral',
        };
    }
}
