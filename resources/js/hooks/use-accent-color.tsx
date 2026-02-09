import { useCallback, useSyncExternalStore } from 'react';

export const ACCENT_COLORS = [
    { name: 'Default', value: 'default' },
    { name: 'Blue', value: '#4146F8' },
    { name: 'Red', value: '#EF4444' },
    { name: 'Green', value: '#22C55E' },
    { name: 'Cyan', value: '#06B6D4' },
    { name: 'Purple', value: '#A855F7' },
    { name: 'Orange', value: '#F97316' },
] as const;

export type AccentColor = (typeof ACCENT_COLORS)[number]['value'];

const listeners = new Set<() => void>();
let currentAccentColor: AccentColor = 'default';

const setCookie = (name: string, value: string, days = 365): void => {
    if (typeof document === 'undefined') return;
    const maxAge = days * 24 * 60 * 60;
    document.cookie = `${name}=${value};path=/;max-age=${maxAge};SameSite=Lax`;
};

const getStoredAccentColor = (): AccentColor => {
    if (typeof window === 'undefined') return 'default';
    return (localStorage.getItem('accentColor') as AccentColor) || 'default';
};

const applyAccentColor = (color: AccentColor): void => {
    if (typeof document === 'undefined') return;

    const root = document.documentElement;

    if (color === 'default') {
        root.style.removeProperty('--primary');
        root.style.removeProperty('--primary-foreground');
        root.style.removeProperty('--ring');
        root.style.removeProperty('--sidebar-primary');
    } else {
        root.style.setProperty('--primary', color);
        root.style.setProperty('--ring', color);
        root.style.setProperty('--sidebar-primary', color);
        root.style.setProperty('--primary-foreground', '#ffffff');
    }
};

const subscribe = (callback: () => void) => {
    listeners.add(callback);
    return () => listeners.delete(callback);
};

const notify = (): void => listeners.forEach((listener) => listener());

export function initializeAccentColor(): void {
    if (typeof window === 'undefined') return;

    currentAccentColor = getStoredAccentColor();
    applyAccentColor(currentAccentColor);
}

export function useAccentColor() {
    const accentColor = useSyncExternalStore(
        subscribe,
        () => currentAccentColor,
        () => 'default' as AccentColor,
    );

    const updateAccentColor = useCallback((color: AccentColor): void => {
        currentAccentColor = color;
        localStorage.setItem('accentColor', color);
        setCookie('accentColor', color);
        applyAccentColor(color);
        notify();
    }, []);

    return { accentColor, updateAccentColor } as const;
}
