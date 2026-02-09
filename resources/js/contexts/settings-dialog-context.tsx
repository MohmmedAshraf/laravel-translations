import { usePage } from '@inertiajs/react';
import { createContext, useContext, useState, type ReactNode } from 'react';
import { SettingsDialog } from '@/components/settings';
import type { SharedData } from '@/types';

interface SettingsDialogContextType {
    open: () => void;
    close: () => void;
    isOpen: boolean;
}

const SettingsDialogContext = createContext<SettingsDialogContextType | null>(
    null,
);

export function SettingsDialogProvider({ children }: { children: ReactNode }) {
    const [isOpen, setIsOpen] = useState(false);
    const { auth, isContributorMode } = usePage<SharedData>().props;

    const open = () => setIsOpen(true);
    const close = () => setIsOpen(false);

    return (
        <SettingsDialogContext.Provider value={{ open, close, isOpen }}>
            {children}
            {auth.user && (
                <SettingsDialog
                    open={isOpen}
                    onOpenChange={setIsOpen}
                    user={auth.user}
                    showAccountSections={isContributorMode}
                />
            )}
        </SettingsDialogContext.Provider>
    );
}

export function useSettingsDialog() {
    const context = useContext(SettingsDialogContext);
    if (!context) {
        throw new Error(
            'useSettingsDialog must be used within a SettingsDialogProvider',
        );
    }
    return context;
}
