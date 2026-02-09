import type { ReactNode } from 'react';
import { Button } from '@/components/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuSeparator,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import {
    Tooltip,
    TooltipContent,
    TooltipTrigger,
} from '@/components/ui/tooltip';
import { MenuDots } from '@/lib/icons';
import type { IconComponent } from '@/lib/icons';
import { cn } from '@/lib/utils';

export interface RowAction {
    label: string;
    icon: IconComponent;
    onClick: (e: React.MouseEvent) => void;
    variant?: 'default' | 'destructive';
    disabled?: boolean;
    hidden?: boolean;
}

interface DataTableRowActionsProps {
    actions: RowAction[];
    separator?: number[];
}

export function DataTableRowActions({
    actions,
    separator = [],
}: DataTableRowActionsProps) {
    const visibleActions = actions.filter((action) => !action.hidden);

    if (visibleActions.length === 0) {
        return null;
    }

    if (visibleActions.length <= 1) {
        return (
            <div className="flex items-center gap-0.5">
                {visibleActions.map((action, index) => {
                    const Icon = action.icon;

                    return (
                        <Tooltip key={index}>
                            <TooltipTrigger asChild>
                                <Button
                                    variant="ghost"
                                    size="sm"
                                    className={cn(
                                        'size-8 p-0',
                                        action.variant === 'destructive' &&
                                            'text-red-600 hover:text-red-600 dark:text-red-400',
                                        action.disabled &&
                                            'pointer-events-auto cursor-not-allowed opacity-40',
                                    )}
                                    onClick={(e) => {
                                        e.stopPropagation();
                                        if (!action.disabled) {
                                            action.onClick(e);
                                        }
                                    }}
                                    aria-disabled={action.disabled || undefined}
                                >
                                    <Icon className="size-4" />
                                </Button>
                            </TooltipTrigger>
                            <TooltipContent>{action.label}</TooltipContent>
                        </Tooltip>
                    );
                })}
            </div>
        );
    }

    const elements: ReactNode[] = [];

    visibleActions.forEach((action, index) => {
        const Icon = action.icon;

        elements.push(
            <DropdownMenuItem
                key={index}
                onSelect={(e) => {
                    e.stopPropagation();
                    action.onClick(e as unknown as React.MouseEvent);
                }}
                disabled={action.disabled}
                className={cn(
                    action.variant === 'destructive' &&
                        'text-red-600 focus:text-red-600 dark:text-red-400',
                )}
            >
                <Icon className="size-4" />
                {action.label}
            </DropdownMenuItem>,
        );

        if (separator.includes(index) && index < visibleActions.length - 1) {
            elements.push(<DropdownMenuSeparator key={`sep-${index}`} />);
        }
    });

    return (
        <DropdownMenu>
            <DropdownMenuTrigger asChild>
                <Button
                    variant="ghost"
                    size="sm"
                    className="size-8 p-0"
                    onClick={(e) => e.stopPropagation()}
                >
                    <MenuDots className="size-4" />
                </Button>
            </DropdownMenuTrigger>
            <DropdownMenuContent align="end">{elements}</DropdownMenuContent>
        </DropdownMenu>
    );
}
