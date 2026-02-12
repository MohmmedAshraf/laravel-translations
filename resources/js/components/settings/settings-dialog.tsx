import * as DialogPrimitive from '@radix-ui/react-dialog';
import { ChevronDown, X } from 'lucide-react';
import { useState } from 'react';
import { Button } from '@/components/ui/button';
import { Dialog, DialogTitle } from '@/components/ui/dialog';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { cn } from '@/lib/utils';
import type { User } from '@/types';
import { AppearanceSection } from './sections/appearance-section';
import { PasswordSection } from './sections/password-section';
import { ProfileSection } from './sections/profile-section';
import { SettingsNav, type SettingsSection } from './settings-nav';

interface SettingsDialogProps {
    open: boolean;
    onOpenChange: (open: boolean) => void;
    user: User;
    showAccountSections?: boolean;
}

const sectionLabels: Record<SettingsSection, string> = {
    appearance: 'Appearance',
    profile: 'Profile',
    password: 'Password',
};

export function SettingsDialog({
    open,
    onOpenChange,
    user,
    showAccountSections = false,
}: SettingsDialogProps) {
    const [activeSection, setActiveSection] =
        useState<SettingsSection>('appearance');
    const [expandedGroups, setExpandedGroups] = useState<string[]>([
        'general',
        'account',
    ]);

    const visibleSections: SettingsSection[] = showAccountSections
        ? ['appearance', 'profile', 'password']
        : ['appearance'];

    const toggleGroup = (group: string) => {
        setExpandedGroups((prev) =>
            prev.includes(group)
                ? prev.filter((g) => g !== group)
                : [...prev, group],
        );
    };

    return (
        <Dialog open={open} onOpenChange={onOpenChange}>
            <DialogPrimitive.Portal>
                <DialogPrimitive.Overlay
                    className={cn(
                        'fixed inset-0 z-50 bg-black/80',
                        'data-[state=closed]:animate-out data-[state=open]:animate-in',
                        'data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0',
                    )}
                />
                <DialogPrimitive.Content
                    className={cn(
                        'fixed z-50 bg-background shadow-lg',
                        'data-[state=closed]:animate-out data-[state=open]:animate-in',
                        'data-[state=closed]:fade-out-0 data-[state=open]:fade-in-0',
                        'duration-200',
                        'inset-0',
                        'md:inset-auto md:top-[50%] md:left-[50%] md:h-150 md:w-200 md:translate-x-[-50%] md:translate-y-[-50%] md:rounded-lg md:border',
                        'md:data-[state=closed]:zoom-out-95 md:data-[state=open]:zoom-in-95',
                    )}
                >
                    <DialogTitle className="sr-only">Settings</DialogTitle>

                    {/* Mobile Header */}
                    <div className="flex items-center justify-between border-b border-border p-4 md:hidden">
                        <DropdownMenu>
                            <DropdownMenuTrigger asChild>
                                <Button variant="outline" className="gap-2">
                                    {sectionLabels[activeSection]}
                                    <ChevronDown className="size-4" />
                                </Button>
                            </DropdownMenuTrigger>
                            <DropdownMenuContent align="start">
                                {visibleSections.map((section) => (
                                    <DropdownMenuItem
                                        key={section}
                                        onClick={() =>
                                            setActiveSection(section)
                                        }
                                        className={cn(
                                            activeSection === section &&
                                                'bg-accent',
                                        )}
                                    >
                                        {sectionLabels[section]}
                                    </DropdownMenuItem>
                                ))}
                            </DropdownMenuContent>
                        </DropdownMenu>
                        <DialogPrimitive.Close className="rounded-sm opacity-70 ring-offset-background transition-opacity hover:opacity-100 focus:ring-2 focus:ring-ring focus:ring-offset-2 focus:outline-none">
                            <X className="size-5" />
                            <span className="sr-only">Close</span>
                        </DialogPrimitive.Close>
                    </div>

                    <div className="flex h-full flex-col md:flex-row md:overflow-hidden md:rounded-lg">
                        {/* Desktop Sidebar */}
                        {showAccountSections && (
                            <div className="hidden md:block">
                                <SettingsNav
                                    activeSection={activeSection}
                                    expandedGroups={expandedGroups}
                                    onSectionChange={setActiveSection}
                                    onToggleGroup={toggleGroup}
                                />
                            </div>
                        )}

                        {/* Content */}
                        <div className="relative flex flex-1 flex-col overflow-hidden">
                            {/* Desktop Close Button */}
                            <DialogPrimitive.Close className="absolute top-4 right-4 z-10 hidden rounded-sm opacity-70 ring-offset-background transition-opacity hover:opacity-100 focus:ring-2 focus:ring-ring focus:ring-offset-2 focus:outline-none md:block">
                                <X className="size-4" />
                                <span className="sr-only">Close</span>
                            </DialogPrimitive.Close>

                            {activeSection === 'appearance' && (
                                <AppearanceSection />
                            )}
                            {activeSection === 'profile' && (
                                <ProfileSection user={user} />
                            )}
                            {activeSection === 'password' && (
                                <PasswordSection />
                            )}
                        </div>
                    </div>
                </DialogPrimitive.Content>
            </DialogPrimitive.Portal>
        </Dialog>
    );
}
