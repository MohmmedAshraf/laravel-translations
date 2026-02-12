import {
    SidebarGroup,
    SidebarGroupContent,
    SidebarSeparator,
} from '@/components/ui/sidebar';
import type { TranslationKey } from '@/types';

interface SidebarDetailsSectionProps {
    translationKey: TranslationKey;
    extraDetailsBefore?: React.ReactNode;
    extraDetailsAfter?: React.ReactNode;
}

function formatDate(dateString?: string): string {
    if (!dateString) {
        return '\u2014';
    }
    return new Date(dateString).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
    });
}

function DetailRow({
    label,
    children,
}: {
    label: string;
    children: React.ReactNode;
}) {
    return (
        <div className="flex items-center justify-between">
            <span className="text-xs text-sidebar-foreground/50">{label}</span>
            {children}
        </div>
    );
}

export { DetailRow, formatDate };

export function SidebarDetailsSection({
    translationKey,
    extraDetailsBefore,
    extraDetailsAfter,
}: SidebarDetailsSectionProps) {
    const hasFormat = translationKey.is_html || translationKey.is_plural;

    return (
        <>
            <SidebarGroup className="p-0">
                <SidebarGroupContent className="px-4 py-3">
                    <div className="space-y-2.5">
                        {extraDetailsBefore}

                        <DetailRow label="Format">
                            {hasFormat ? (
                                <div className="flex items-center gap-1">
                                    {translationKey.is_html && (
                                        <span className="rounded bg-sidebar-accent px-1.5 py-0.5 text-xs">
                                            HTML
                                        </span>
                                    )}
                                    {translationKey.is_plural && (
                                        <span className="rounded bg-sidebar-accent px-1.5 py-0.5 text-xs">
                                            Plural
                                        </span>
                                    )}
                                </div>
                            ) : (
                                <span className="text-xs text-sidebar-foreground/50">
                                    Plain text
                                </span>
                            )}
                        </DetailRow>

                        {extraDetailsAfter}

                        {translationKey.created_at && (
                            <DetailRow label="Created">
                                <span className="text-xs text-sidebar-foreground/70">
                                    {formatDate(translationKey.created_at)}
                                </span>
                            </DetailRow>
                        )}

                        {translationKey.updated_at && (
                            <DetailRow label="Updated">
                                <span className="text-xs text-sidebar-foreground/70">
                                    {formatDate(translationKey.updated_at)}
                                </span>
                            </DetailRow>
                        )}
                    </div>
                </SidebarGroupContent>
            </SidebarGroup>
            <SidebarSeparator className="mx-0" />
        </>
    );
}
