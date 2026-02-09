import { ChevronDown, Palette, User } from 'lucide-react';
import { cn } from '@/lib/utils';

export type SettingsSection = 'appearance' | 'profile' | 'password';

interface SettingsNavProps {
    activeSection: SettingsSection;
    expandedGroups: string[];
    onSectionChange: (section: SettingsSection) => void;
    onToggleGroup: (group: string) => void;
}

export function SettingsNav({
    activeSection,
    expandedGroups,
    onSectionChange,
    onToggleGroup,
}: SettingsNavProps) {
    return (
        <div className="flex h-full w-[240px] flex-col border-r border-border bg-muted/30 p-4">
            <h2 className="mb-6 text-xl font-semibold">Settings</h2>

            <nav className="flex-1 space-y-1">
                <NavGroup
                    icon={<Palette className="h-4 w-4" />}
                    label="General"
                    isExpanded={expandedGroups.includes('general')}
                    isActive={activeSection === 'appearance'}
                    onToggle={() => onToggleGroup('general')}
                >
                    <NavItem
                        label="Appearance"
                        isActive={activeSection === 'appearance'}
                        onClick={() => onSectionChange('appearance')}
                    />
                </NavGroup>

                <NavGroup
                    icon={<User className="h-4 w-4" />}
                    label="Account"
                    isExpanded={expandedGroups.includes('account')}
                    isActive={
                        activeSection === 'profile' ||
                        activeSection === 'password'
                    }
                    onToggle={() => onToggleGroup('account')}
                >
                    <NavItem
                        label="Profile"
                        isActive={activeSection === 'profile'}
                        onClick={() => onSectionChange('profile')}
                    />
                    <NavItem
                        label="Password"
                        isActive={activeSection === 'password'}
                        onClick={() => onSectionChange('password')}
                    />
                </NavGroup>
            </nav>
        </div>
    );
}

interface NavGroupProps {
    icon: React.ReactNode;
    label: string;
    isExpanded: boolean;
    isActive: boolean;
    onToggle: () => void;
    children: React.ReactNode;
}

function NavGroup({
    icon,
    label,
    isExpanded,
    isActive,
    onToggle,
    children,
}: NavGroupProps) {
    return (
        <div>
            <button
                onClick={onToggle}
                className={cn(
                    'flex w-full items-center justify-between rounded-lg px-3 py-2 text-sm font-medium transition-colors',
                    isActive
                        ? 'bg-primary/10 text-primary'
                        : 'text-foreground hover:bg-accent',
                )}
            >
                <span className="flex items-center gap-3">
                    {icon}
                    {label}
                </span>
                <ChevronDown
                    className={cn(
                        'h-4 w-4 transition-transform',
                        isExpanded && 'rotate-180',
                    )}
                />
            </button>
            {isExpanded && (
                <div className="mt-1 ml-7 space-y-0.5">{children}</div>
            )}
        </div>
    );
}

interface NavItemProps {
    label: string;
    isActive: boolean;
    onClick: () => void;
}

function NavItem({ label, isActive, onClick }: NavItemProps) {
    return (
        <button
            onClick={onClick}
            className={cn(
                'w-full rounded-md px-3 py-1.5 text-left text-sm transition-colors',
                isActive
                    ? 'bg-accent font-medium text-foreground'
                    : 'text-muted-foreground hover:text-foreground',
            )}
        >
            {label}
        </button>
    );
}
