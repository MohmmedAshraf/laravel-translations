import { useState } from 'react';
import {
    AltArrowDown,
    AltArrowUp,
    CheckCircle,
    DangerCircle,
    DangerTriangle,
} from '@/lib/icons';
import type { QualityIssue } from '@/pages/translations/phrases/edit';

interface ValidationIssuesAlertProps {
    formError?: string;
    qualityIssues?: QualityIssue[];
}

const SEVERITY_CONFIG = {
    error: {
        icon: DangerCircle,
        bg: 'bg-red-50/95 dark:bg-red-950/80',
        border: 'border-red-200 dark:border-red-800',
        iconColor: 'text-red-500',
        textColor: 'text-red-800 dark:text-red-200',
        suggestionColor: 'text-red-600/70 dark:text-red-400/70',
    },
    warning: {
        icon: DangerTriangle,
        bg: 'bg-amber-50/95 dark:bg-amber-950/80',
        border: 'border-amber-200 dark:border-amber-800',
        iconColor: 'text-amber-500',
        textColor: 'text-amber-800 dark:text-amber-200',
        suggestionColor: 'text-amber-600/70 dark:text-amber-400/70',
    },
    info: {
        icon: CheckCircle,
        bg: 'bg-blue-50/95 dark:bg-blue-950/80',
        border: 'border-blue-200 dark:border-blue-800',
        iconColor: 'text-blue-500',
        textColor: 'text-blue-800 dark:text-blue-200',
        suggestionColor: 'text-blue-600/70 dark:text-blue-400/70',
    },
};

type AlertItem = {
    severity: 'error' | 'warning' | 'info';
    message: string;
    suggestion?: string | null;
};

export function ValidationIssuesAlert({
    formError,
    qualityIssues = [],
}: ValidationIssuesAlertProps) {
    const [expanded, setExpanded] = useState(false);

    const items: AlertItem[] = [];

    if (formError) {
        items.push({ severity: 'error', message: formError });
    }

    for (const issue of qualityIssues) {
        items.push({
            severity: issue.severity,
            message: issue.message,
            suggestion: issue.suggestion,
        });
    }

    if (items.length === 0) {
        return null;
    }

    const errorCount = items.filter((i) => i.severity === 'error').length;
    const warningCount = items.filter((i) => i.severity === 'warning').length;
    const infoCount = items.filter((i) => i.severity === 'info').length;

    const barSeverity =
        errorCount > 0 ? 'error' : warningCount > 0 ? 'warning' : 'info';
    const barConfig = SEVERITY_CONFIG[barSeverity];
    const BarIcon = barConfig.icon;

    const parts: string[] = [];
    if (errorCount)
        parts.push(`${errorCount} error${errorCount > 1 ? 's' : ''}`);
    if (warningCount)
        parts.push(`${warningCount} warning${warningCount > 1 ? 's' : ''}`);
    if (infoCount) parts.push(`${infoCount} info`);

    if (items.length === 1) {
        const item = items[0];
        const config = SEVERITY_CONFIG[item.severity];
        const Icon = config.icon;

        return (
            <div className="absolute inset-x-2 bottom-2 z-10">
                <div
                    className={`flex items-center gap-2 rounded-lg border px-3 py-1.5 shadow-sm backdrop-blur-sm ${config.bg} ${config.border}`}
                >
                    <Icon className={`size-3.5 shrink-0 ${config.iconColor}`} />
                    <span className={`text-xs ${config.textColor}`}>
                        {item.message}
                    </span>
                </div>
            </div>
        );
    }

    return (
        <div className="absolute inset-x-2 bottom-2 z-10">
            <div
                className={`overflow-hidden rounded-lg border shadow-sm backdrop-blur-sm ${barConfig.bg} ${barConfig.border}`}
            >
                <button
                    type="button"
                    onClick={() => setExpanded(!expanded)}
                    className={`flex w-full items-center gap-2 px-3 py-1.5 text-xs ${barConfig.textColor}`}
                >
                    <BarIcon
                        className={`size-3.5 shrink-0 ${barConfig.iconColor}`}
                    />
                    <span className="flex-1 text-left font-medium">
                        {parts.join(', ')}
                    </span>
                    {expanded ? (
                        <AltArrowDown className="size-3.5 shrink-0" />
                    ) : (
                        <AltArrowUp className="size-3.5 shrink-0" />
                    )}
                </button>

                {expanded && (
                    <div className="flex flex-col gap-1 border-t border-inherit px-3 pt-1.5 pb-2">
                        {items.map((item, idx) => {
                            const config = SEVERITY_CONFIG[item.severity];
                            const Icon = config.icon;
                            return (
                                <div
                                    key={idx}
                                    className="flex items-start gap-2 text-xs"
                                >
                                    <Icon
                                        className={`mt-0.5 size-3 shrink-0 ${config.iconColor}`}
                                    />
                                    <span className={config.textColor}>
                                        {item.message}
                                        {item.suggestion && (
                                            <span
                                                className={`ml-1 ${config.suggestionColor}`}
                                            >
                                                — {item.suggestion}
                                            </span>
                                        )}
                                    </span>
                                </div>
                            );
                        })}
                    </div>
                )}
            </div>
        </div>
    );
}
