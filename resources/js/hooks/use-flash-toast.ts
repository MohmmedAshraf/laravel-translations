import { usePage } from '@inertiajs/react';
import { useEffect, useRef } from 'react';
import { toast } from 'sonner';

interface FlashMessages {
    success?: string | null;
    error?: string | null;
    warning?: string | null;
    info?: string | null;
}

export function useFlashToast(): void {
    const flash = usePage<{ flash: FlashMessages }>().props.flash;
    const shownRef = useRef<string | null>(null);

    useEffect(() => {
        if (!flash) return;

        const key = JSON.stringify(flash);
        if (key === shownRef.current) return;
        shownRef.current = key;

        if (flash.success) toast.success(flash.success);
        if (flash.error) toast.error(flash.error);
        if (flash.warning) toast.warning(flash.warning);
        if (flash.info) toast.info(flash.info);
    }, [flash]);
}
