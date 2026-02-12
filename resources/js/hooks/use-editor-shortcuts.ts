import { useEffect } from 'react';

interface UseEditorShortcutsOptions {
    onSave?: () => void;
    onPrevious?: () => void;
    onNext?: () => void;
}

export function useEditorShortcuts({
    onSave,
    onPrevious,
    onNext,
}: UseEditorShortcutsOptions): void {
    useEffect(() => {
        function handleKeyDown(e: KeyboardEvent): void {
            if ((e.metaKey || e.ctrlKey) && e.key === 's') {
                e.preventDefault();
                onSave?.();
            }

            if (e.altKey && e.key === 'ArrowLeft') {
                e.preventDefault();
                onPrevious?.();
            }

            if (e.altKey && e.key === 'ArrowRight') {
                e.preventDefault();
                onNext?.();
            }
        }

        document.addEventListener('keydown', handleKeyDown);

        return () => document.removeEventListener('keydown', handleKeyDown);
    }, [onSave, onPrevious, onNext]);
}
