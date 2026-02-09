<?php

declare(strict_types=1);

namespace Outhebox\Translations\Support\DataTable;

use BackedEnum;
use Closure;
use Outhebox\Translations\Contracts\HasColor;
use Outhebox\Translations\Contracts\HasIcon;
use Outhebox\Translations\Contracts\HasLabel;

class Column
{
    private string $id;

    private ?string $header = null;

    private string $type = 'text';

    private bool $sortable = false;

    private bool $searchable = false;

    private bool $visible = true;

    private bool $canHide = true;

    private ?int $minSize = null;

    private ?int $maxSize = null;

    private bool $fill = false;

    private bool $centered = false;

    private ?int $truncate = null;

    private ?string $emptyValue = null;

    private ?array $badgeMap = null;

    private ?string $badgeEnum = null;

    private ?string $accessorKey = null;

    private ?string $headerIcon = null;

    private string|Closure|null $sortColumn = null;

    private function __construct(string $id, ?string $header = null)
    {
        $this->id = $id;
        $this->header = $header;
    }

    public static function make(string $id, ?string $header = null): self
    {
        return new self($id, $header);
    }

    public function text(): self
    {
        $this->type = 'text';

        return $this;
    }

    public function mono(): self
    {
        $this->type = 'mono';

        return $this;
    }

    public function date(): self
    {
        $this->type = 'date';

        return $this;
    }

    public function datetime(): self
    {
        $this->type = 'datetime';

        return $this;
    }

    public function list(): self
    {
        $this->type = 'list';

        return $this;
    }

    public function boolean(): self
    {
        $this->type = 'boolean';

        return $this;
    }

    public function progress(): self
    {
        $this->type = 'progress';

        return $this;
    }

    public function statusIcon(string|array|null $enumOrMap = null): self
    {
        $this->type = 'status-icon';

        if (is_string($enumOrMap) && is_subclass_of($enumOrMap, BackedEnum::class)) {
            $this->badgeEnum = $enumOrMap;
            $this->badgeMap = $this->buildBadgeMapFromEnum($enumOrMap);
        } elseif (is_array($enumOrMap)) {
            $this->badgeMap = $enumOrMap;
        }

        return $this;
    }

    public function language(): self
    {
        $this->type = 'language';

        return $this;
    }

    public function relativeTime(): self
    {
        $this->type = 'relative-time';

        return $this;
    }

    public function fill(): self
    {
        $this->fill = true;

        return $this;
    }

    public function badge(string|array|null $enumOrMap = null): self
    {
        $this->type = 'badge';

        if (is_string($enumOrMap) && is_subclass_of($enumOrMap, BackedEnum::class)) {
            $this->badgeEnum = $enumOrMap;
            $this->badgeMap = $this->buildBadgeMapFromEnum($enumOrMap);
        } elseif (is_array($enumOrMap)) {
            $this->badgeMap = $enumOrMap;
        }

        return $this;
    }

    public function sortable(bool $sortable = true): self
    {
        $this->sortable = $sortable;

        return $this;
    }

    public function sortColumn(string|Closure $column): self
    {
        $this->sortColumn = $column;

        return $this;
    }

    public function getSortColumn(): string|Closure|null
    {
        return $this->sortColumn;
    }

    public function searchable(bool $searchable = true): self
    {
        $this->searchable = $searchable;

        return $this;
    }

    public function hidden(): self
    {
        $this->visible = false;

        return $this;
    }

    public function fixed(): self
    {
        $this->canHide = false;

        return $this;
    }

    public function minSize(int $pixels): self
    {
        $this->minSize = $pixels;

        return $this;
    }

    public function maxSize(int $pixels): self
    {
        $this->maxSize = $pixels;

        return $this;
    }

    public function truncate(int $length): self
    {
        $this->truncate = $length;

        return $this;
    }

    public function empty(string $value): self
    {
        $this->emptyValue = $value;

        return $this;
    }

    public function accessor(string $key): self
    {
        $this->accessorKey = $key;

        return $this;
    }

    public function centered(): self
    {
        $this->centered = true;

        return $this;
    }

    public function headerIcon(string $icon): self
    {
        $this->headerIcon = $icon;

        return $this;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getBadgeEnum(): ?string
    {
        return $this->badgeEnum;
    }

    public function setBadgeMap(array $map): self
    {
        $this->badgeMap = $map;

        return $this;
    }

    public function toArray(): array
    {
        $column = [
            'id' => $this->id,
            'header' => $this->header ?? str($this->id)->headline()->toString(),
            'type' => $this->type,
        ];

        $this->applyOptionalFields($column);
        $this->applyCenteredStyles($column);

        return $column;
    }

    private function applyOptionalFields(array &$column): void
    {
        $conditionals = [
            'sortable' => $this->sortable ? true : null,
            'searchable' => $this->searchable ? true : null,
            'visible' => ! $this->visible ? false : null,
            'canHide' => ! $this->canHide ? false : null,
            'minSize' => $this->minSize,
            'maxSize' => $this->maxSize,
            'truncate' => $this->truncate,
            'emptyValue' => $this->emptyValue,
            'badgeMap' => $this->badgeMap,
            'accessorKey' => $this->accessorKey,
            'fill' => $this->fill ? true : null,
            'headerIcon' => $this->headerIcon,
        ];

        foreach ($conditionals as $key => $value) {
            if ($value !== null) {
                $column[$key] = $value;
            }
        }
    }

    private function applyCenteredStyles(array &$column): void
    {
        if (! $this->centered) {
            return;
        }

        $column['cellClassName'] = 'text-center [&>div]:justify-center';
        $column['headerClassName'] = '[&>div]:justify-center';
    }

    private function buildBadgeMapFromEnum(string $enumClass): array
    {
        $map = [];

        foreach ($enumClass::cases() as $case) {
            $entry = [
                'variant' => 'outline',
                'label' => $case instanceof HasLabel ? $case->getLabel() : $case->name,
            ];

            if ($case instanceof HasColor) {
                $color = $case->getColor();
                $entry['color'] = $color;
                $entry['variant'] = match ($color) {
                    'green' => 'default',
                    'blue' => 'secondary',
                    'red' => 'destructive',
                    default => 'outline',
                };
            }

            if ($case instanceof HasIcon && $icon = $case->getIcon()) {
                $entry['icon'] = $icon;
            }

            $map[$case->value] = $entry;
        }

        return $map;
    }
}
