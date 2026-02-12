import { KeyNavigation } from '@/components/translations/key-navigation';
import { Badge } from '@/components/ui/badge';
import type { TranslationKey } from '@/types';
import { statusColors, statusLabels } from '@/types/translations';

interface EditorHeaderProps {
    translationKey: TranslationKey;
    status?: string;
    previousHref: string | null;
    nextHref: string | null;
    backHref: string;
    actions?: React.ReactNode;
}

export function EditorHeader({
    translationKey,
    status,
    previousHref,
    nextHref,
    backHref,
    actions,
}: EditorHeaderProps) {
    return (
        <div className="flex shrink-0 items-center justify-between border-b px-4 py-2.5">
            <div className="flex min-w-0 items-center gap-2">
                <h1 className="truncate font-mono text-sm font-semibold">
                    {translationKey.key}
                </h1>
                <Badge variant="secondary" className="shrink-0">
                    {translationKey.group.name}
                </Badge>
                {status && (
                    <Badge
                        variant={statusColors[status] ?? 'secondary'}
                        className="shrink-0"
                    >
                        {statusLabels[status] ?? status}
                    </Badge>
                )}
            </div>
            <div className="flex items-center gap-2">
                {actions}
                <KeyNavigation
                    previousHref={previousHref}
                    nextHref={nextHref}
                    backHref={backHref}
                />
            </div>
        </div>
    );
}
