import {
    forwardRef,
    useCallback,
    useEffect,
    useImperativeHandle,
    useRef,
} from 'react';
import { Spinner } from '@/components/ui/spinner';
import { CloseCircle, Magnifer } from '@/lib/icons';
import { cn } from '@/lib/utils';

interface SearchBoxProps {
    value: string;
    loading?: boolean;
    showClearButton?: boolean;
    onChange: (value: string) => void;
    onChangeDebounced?: (value: string) => void;
    debounceTimeoutMs?: number;
    inputClassName?: string;
    placeholder?: string;
    disabled?: boolean;
}

export const SearchBox = forwardRef<HTMLInputElement, SearchBoxProps>(
    (
        {
            value,
            loading,
            showClearButton = true,
            onChange,
            onChangeDebounced,
            debounceTimeoutMs = 500,
            inputClassName,
            placeholder,
            disabled,
        },
        forwardedRef,
    ) => {
        const inputRef = useRef<HTMLInputElement>(null);
        const debounceRef = useRef<NodeJS.Timeout | null>(null);
        useImperativeHandle(forwardedRef, () => inputRef.current!);

        const handleChange = useCallback(
            (newValue: string) => {
                onChange(newValue);

                if (debounceRef.current) {
                    clearTimeout(debounceRef.current);
                }

                debounceRef.current = setTimeout(() => {
                    onChangeDebounced?.(newValue);
                }, debounceTimeoutMs);
            },
            [onChange, onChangeDebounced, debounceTimeoutMs],
        );

        const handleClear = useCallback(() => {
            onChange('');
            onChangeDebounced?.('');
            inputRef.current?.focus();
        }, [onChange, onChangeDebounced]);

        useEffect(() => {
            const onKeyDown = (e: KeyboardEvent) => {
                const target = e.target as HTMLElement;
                if (
                    e.key === '/' &&
                    target.tagName !== 'INPUT' &&
                    target.tagName !== 'TEXTAREA'
                ) {
                    e.preventDefault();
                    inputRef.current?.focus();
                } else if (e.key === 'Escape') {
                    if (value.length > 0) {
                        handleClear();
                    } else {
                        inputRef.current?.blur();
                    }
                }
            };

            document.addEventListener('keydown', onKeyDown);
            return () => document.removeEventListener('keydown', onKeyDown);
        }, [value, handleClear]);

        useEffect(() => {
            return () => {
                if (debounceRef.current) {
                    clearTimeout(debounceRef.current);
                }
            };
        }, []);

        return (
            <div className="relative">
                <div className="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4">
                    {loading && value.length > 0 ? (
                        <Spinner className="size-4" />
                    ) : (
                        <Magnifer className="size-4 text-neutral-400" />
                    )}
                </div>
                <input
                    ref={inputRef}
                    type="text"
                    className={cn(
                        'peer w-full rounded-md border border-neutral-200 px-10 text-neutral-900 outline-none placeholder:text-neutral-400 sm:text-sm dark:border-neutral-700 dark:bg-neutral-800 dark:text-neutral-100 dark:placeholder:text-neutral-500',
                        'h-10 transition-all focus:border-neutral-400 focus:ring-2 focus:ring-neutral-100 dark:focus:border-neutral-500 dark:focus:ring-neutral-800',
                        disabled &&
                            'cursor-not-allowed opacity-50 focus:border-neutral-200 focus:ring-0 dark:focus:border-neutral-700',
                        inputClassName,
                    )}
                    placeholder={placeholder || 'Search...'}
                    value={value}
                    onChange={(e) => handleChange(e.target.value)}
                    autoCapitalize="none"
                    disabled={disabled}
                />
                {showClearButton && value.length > 0 && !disabled ? (
                    <button
                        type="button"
                        onClick={handleClear}
                        className="pointer-events-auto absolute inset-y-0 right-0 flex items-center pr-4"
                    >
                        <CloseCircle className="size-4 text-neutral-400 hover:text-neutral-600 dark:hover:text-neutral-300" />
                    </button>
                ) : (
                    !value &&
                    !disabled && (
                        <div className="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3">
                            <kbd className="rounded border border-neutral-200 bg-neutral-100 px-1.5 py-0.5 text-xs text-neutral-400 dark:border-neutral-700 dark:bg-neutral-800">
                                /
                            </kbd>
                        </div>
                    )
                )}
            </div>
        );
    },
);

SearchBox.displayName = 'SearchBox';
