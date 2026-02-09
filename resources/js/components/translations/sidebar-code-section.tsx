import { SidebarSection } from '@/components/translations/sidebar-section';
import { Code } from '@/lib/icons';

interface SidebarCodeSectionProps {
    translationKeyId: number;
    isOpen: boolean;
    onToggle: () => void;
}

export function SidebarCodeSection({
    isOpen,
    onToggle,
}: SidebarCodeSectionProps) {
    return (
        <SidebarSection
            icon={Code}
            title="Code Usage"
            isOpen={isOpen}
            onToggle={onToggle}
            contentClassName="px-2 pb-3"
        >
            <p className="px-2 py-3 text-center text-sm text-sidebar-foreground/50">
                Code usage detection is available in the Pro version.
            </p>
        </SidebarSection>
    );
}
