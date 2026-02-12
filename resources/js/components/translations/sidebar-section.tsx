import {
    Collapsible,
    CollapsibleContent,
    CollapsibleTrigger,
} from '@/components/ui/collapsible';
import {
    SidebarGroup,
    SidebarGroupContent,
    SidebarGroupLabel,
    SidebarSeparator,
} from '@/components/ui/sidebar';
import type { IconComponent } from '@/lib/icons';
import { AltArrowRight } from '@/lib/icons';

interface SidebarSectionProps {
    icon: IconComponent;
    title: string;
    isOpen: boolean;
    onToggle: () => void;
    badge?: React.ReactNode;
    children: React.ReactNode;
    contentClassName?: string;
    hideSeparator?: boolean;
}

export function SidebarSection({
    icon: Icon,
    title,
    isOpen,
    onToggle,
    badge,
    children,
    contentClassName = 'px-4 pb-3',
    hideSeparator,
}: SidebarSectionProps) {
    return (
        <>
            <Collapsible open={isOpen} onOpenChange={onToggle}>
                <SidebarGroup className="p-0">
                    <CollapsibleTrigger asChild>
                        <SidebarGroupLabel className="h-10 cursor-pointer gap-2.5 rounded-none px-4 hover:bg-sidebar-accent">
                            <Icon className="size-4 shrink-0 text-sidebar-foreground/60" />
                            <span className="text-sm font-medium text-sidebar-foreground">
                                {title}
                            </span>
                            {badge}
                            <AltArrowRight
                                className={`ml-auto size-4 text-sidebar-foreground/40 transition-transform duration-200 ${isOpen ? 'rotate-90' : ''}`}
                            />
                        </SidebarGroupLabel>
                    </CollapsibleTrigger>
                    <CollapsibleContent>
                        <SidebarGroupContent className={contentClassName}>
                            {children}
                        </SidebarGroupContent>
                    </CollapsibleContent>
                </SidebarGroup>
            </Collapsible>
            {!hideSeparator && <SidebarSeparator className="mx-0" />}
        </>
    );
}
