import { cn } from '@/lib/utils';

export const tableCellClassName = (): string =>
    cn(
        'relative px-4 py-2.5 text-left text-sm leading-6 whitespace-nowrap',
        'border-b border-l border-neutral-200 dark:border-neutral-800',
        'transition-colors duration-75 group-hover/row:bg-neutral-50 dark:group-hover/row:bg-neutral-800/50',
    );
