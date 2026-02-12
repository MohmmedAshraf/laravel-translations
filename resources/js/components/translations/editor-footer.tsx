import { Transition } from '@headlessui/react';
import { Link } from '@inertiajs/react';
import { Button } from '@/components/ui/button';

interface EditorFooterProps {
    backHref: string;
    processing: boolean;
    recentlySuccessful: boolean;
    onSubmit: () => void;
    submitLabel?: string;
    cancelLabel?: string;
    size?: 'sm' | 'lg';
    extraActions?: React.ReactNode;
}

export function EditorFooter({
    backHref,
    processing,
    recentlySuccessful,
    onSubmit,
    submitLabel = 'Save',
    cancelLabel = 'Cancel',
    size = 'sm',
    extraActions,
}: EditorFooterProps) {
    return (
        <div
            className={`flex shrink-0 items-center justify-between border-t px-4 ${size === 'lg' ? 'h-14' : 'h-11'}`}
        >
            <Link href={backHref}>
                <Button
                    variant="ghost"
                    size={size}
                    className={`tracking-wide uppercase ${size === 'sm' ? 'h-7 text-xs' : ''}`}
                >
                    {cancelLabel}
                </Button>
            </Link>
            <div className="flex items-center gap-2">
                <Transition
                    show={recentlySuccessful}
                    enter="transition ease-in-out"
                    enterFrom="opacity-0"
                    leave="transition ease-in-out"
                    leaveTo="opacity-0"
                >
                    <p
                        className={`text-muted-foreground ${size === 'sm' ? 'text-xs' : ''}`}
                    >
                        Saved
                    </p>
                </Transition>
                {extraActions}
                <Button
                    size={size}
                    className={`tracking-wide uppercase ${size === 'sm' ? 'h-7 text-xs' : ''}`}
                    onClick={onSubmit}
                    disabled={processing}
                >
                    {processing ? 'Saving...' : submitLabel}
                </Button>
            </div>
        </div>
    );
}
