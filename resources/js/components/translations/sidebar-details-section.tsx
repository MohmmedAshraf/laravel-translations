import { SidebarSection } from '@/components/translations/sidebar-section';
import { Badge } from '@/components/ui/badge';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { DocumentText } from '@/lib/icons';
import type { TranslationKey } from '@/types';
import { statusColors, statusLabels } from '@/types/translations';

interface SidebarDetailsSectionProps {
    translationKey: TranslationKey;
    status?: string;
    showPrioritySelect: boolean;
    isOpen: boolean;
    onToggle: () => void;
    onPriorityChange: (value: string) => void;
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
        <div className="flex items-center justify-between py-1">
            <span className="text-sm text-sidebar-foreground/60">{label}</span>
            {children}
        </div>
    );
}

export function SidebarDetailsSection({
    translationKey,
    status,
    showPrioritySelect,
    isOpen,
    onToggle,
    onPriorityChange,
}: SidebarDetailsSectionProps) {
    return (
        <SidebarSection
            icon={DocumentText}
            title="Details"
            isOpen={isOpen}
            onToggle={onToggle}
        >
            <div className="space-y-1">
                <DetailRow label="Group">
                    <Badge variant="secondary">
                        {translationKey.group.name}
                    </Badge>
                </DetailRow>

                {status && (
                    <DetailRow label="Status">
                        <Badge variant={statusColors[status] ?? 'secondary'}>
                            {statusLabels[status] ?? status}
                        </Badge>
                    </DetailRow>
                )}

                <DetailRow label="Priority">
                    {showPrioritySelect ? (
                        <Select
                            defaultValue={translationKey.priority ?? 'low'}
                            onValueChange={onPriorityChange}
                        >
                            <SelectTrigger className="h-8 w-28 text-sm">
                                <SelectValue />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="low">Low</SelectItem>
                                <SelectItem value="medium">Medium</SelectItem>
                                <SelectItem value="high">High</SelectItem>
                                <SelectItem value="critical">
                                    Critical
                                </SelectItem>
                            </SelectContent>
                        </Select>
                    ) : (
                        <Badge variant="outline">
                            {translationKey.priority ?? 'Low'}
                        </Badge>
                    )}
                </DetailRow>

                <DetailRow label="Format">
                    <div className="flex items-center gap-1.5">
                        {translationKey.is_html && (
                            <Badge variant="outline">HTML</Badge>
                        )}
                        {translationKey.is_plural && (
                            <Badge variant="outline">Plural</Badge>
                        )}
                        {!translationKey.is_html &&
                            !translationKey.is_plural && (
                                <span className="text-sm text-sidebar-foreground/60">
                                    Plain text
                                </span>
                            )}
                    </div>
                </DetailRow>

                {translationKey.parameters &&
                    translationKey.parameters.length > 0 && (
                        <div className="space-y-1.5 pt-1">
                            <span className="text-sm text-sidebar-foreground/60">
                                Parameters
                            </span>
                            <div className="flex flex-wrap gap-1.5">
                                {translationKey.parameters.map((param) => (
                                    <Badge
                                        key={param}
                                        variant="outline"
                                        className="font-mono"
                                    >
                                        {param}
                                    </Badge>
                                ))}
                            </div>
                        </div>
                    )}

                {translationKey.created_at && (
                    <DetailRow label="Created">
                        <span className="text-sm">
                            {formatDate(translationKey.created_at)}
                        </span>
                    </DetailRow>
                )}
                {translationKey.updated_at && (
                    <DetailRow label="Updated">
                        <span className="text-sm">
                            {formatDate(translationKey.updated_at)}
                        </span>
                    </DetailRow>
                )}
            </div>
        </SidebarSection>
    );
}
