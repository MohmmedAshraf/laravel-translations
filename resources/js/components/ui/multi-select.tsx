import * as React from 'react';
import { Badge } from '@/components/ui/badge';
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/popover';
import { cn } from '@/lib/utils';

export interface MultiSelectOption {
    value: string | number;
    label: string;
    icon?: React.ReactNode;
}

interface MultiSelectProps {
    options: MultiSelectOption[];
    value: (string | number)[];
    onChange: (value: (string | number)[]) => void;
    placeholder?: string;
    emptyMessage?: string;
    className?: string;
}

export function MultiSelect({
    options,
    value,
    onChange,
    placeholder = 'Select...',
    emptyMessage = 'No results found.',
    className,
}: MultiSelectProps) {
    const [open, setOpen] = React.useState(false);
    const [search, setSearch] = React.useState('');
    const inputRef = React.useRef<HTMLInputElement>(null);

    const selected = options.filter((o) => value.includes(o.value));

    const filtered = options.filter(
        (o) =>
            !value.includes(o.value) &&
            o.label.toLowerCase().includes(search.toLowerCase()),
    );

    const handleSelect = (optionValue: string | number) => {
        onChange([...value, optionValue]);
        setSearch('');
    };

    const handleRemove = (optionValue: string | number) => {
        onChange(value.filter((v) => v !== optionValue));
    };

    const handleKeyDown = (e: React.KeyboardEvent<HTMLInputElement>) => {
        if (e.key === 'Backspace' && !search && value.length > 0) {
            onChange(value.slice(0, -1));
        }
        if (e.key === 'Escape') {
            setOpen(false);
        }
    };

    return (
        <Popover open={open} onOpenChange={setOpen}>
            <PopoverTrigger asChild>
                <button
                    type="button"
                    role="combobox"
                    aria-expanded={open}
                    className={cn(
                        'flex min-h-9 w-full flex-wrap items-center gap-1 rounded-md border border-input bg-background px-2.5 py-1.5 text-sm ring-offset-background',
                        'focus-within:ring-2 focus-within:ring-ring focus-within:ring-offset-2',
                        'disabled:cursor-not-allowed disabled:opacity-50',
                        className,
                    )}
                    onClick={() => {
                        setOpen(true);
                        setTimeout(() => inputRef.current?.focus(), 0);
                    }}
                >
                    {selected.map((option) => (
                        <Badge
                            key={option.value}
                            variant="secondary"
                            className="gap-1 pr-1"
                        >
                            {option.icon}
                            {option.label}
                            <span
                                role="button"
                                tabIndex={0}
                                className="ml-0.5 rounded-sm p-0.5 hover:bg-foreground/10"
                                onPointerDown={(e) => {
                                    e.preventDefault();
                                    e.stopPropagation();
                                }}
                                onClick={(e) => {
                                    e.preventDefault();
                                    e.stopPropagation();
                                    handleRemove(option.value);
                                }}
                                onKeyDown={(e) => {
                                    if (e.key === 'Enter') {
                                        handleRemove(option.value);
                                    }
                                }}
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    strokeWidth="2"
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                    className="size-3"
                                >
                                    <path d="M18 6 6 18" />
                                    <path d="m6 6 12 12" />
                                </svg>
                            </span>
                        </Badge>
                    ))}
                    {selected.length === 0 && (
                        <span className="text-muted-foreground">
                            {placeholder}
                        </span>
                    )}
                </button>
            </PopoverTrigger>
            <PopoverContent
                className="w-[var(--radix-popover-trigger-width)] p-0"
                align="start"
                onOpenAutoFocus={(e) => {
                    e.preventDefault();
                    inputRef.current?.focus();
                }}
            >
                <div className="border-b px-3 py-2">
                    <input
                        ref={inputRef}
                        value={search}
                        onChange={(e) => setSearch(e.target.value)}
                        onKeyDown={handleKeyDown}
                        placeholder="Search..."
                        className="w-full bg-transparent text-sm outline-none placeholder:text-muted-foreground"
                    />
                </div>
                <div className="max-h-48 overflow-y-auto p-1">
                    {filtered.length === 0 ? (
                        <p className="px-2 py-4 text-center text-sm text-muted-foreground">
                            {emptyMessage}
                        </p>
                    ) : (
                        filtered.map((option) => (
                            <button
                                key={option.value}
                                type="button"
                                className="flex w-full items-center gap-2 rounded-sm px-2 py-1.5 text-sm outline-none hover:bg-accent hover:text-accent-foreground focus:bg-accent focus:text-accent-foreground"
                                onClick={() => handleSelect(option.value)}
                            >
                                {option.icon}
                                {option.label}
                            </button>
                        ))
                    )}
                </div>
            </PopoverContent>
        </Popover>
    );
}
