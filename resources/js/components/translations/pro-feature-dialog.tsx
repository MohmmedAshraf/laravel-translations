import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Stars } from '@/lib/icons';

interface ProFeatureDialogProps {
    title: string;
    description: string;
    open: boolean;
    onOpenChange: (open: boolean) => void;
}

export function ProFeatureDialog({
    title,
    description,
    open,
    onOpenChange,
}: ProFeatureDialogProps) {
    return (
        <Dialog open={open} onOpenChange={onOpenChange}>
            <DialogContent className="sm:max-w-md">
                <DialogHeader>
                    <DialogTitle className="flex items-center gap-2">
                        <Stars className="size-5" />
                        {title}
                    </DialogTitle>
                    <DialogDescription>{description}</DialogDescription>
                </DialogHeader>

                <div className="space-y-4">
                    <div className="rounded-lg border border-neutral-200 bg-neutral-50 p-6 text-center dark:border-neutral-700 dark:bg-neutral-800/50">
                        <Stars className="mx-auto mb-3 size-8 text-neutral-400" />
                        <p className="text-sm font-medium text-neutral-900 dark:text-neutral-100">
                            AI Translation is a Pro feature
                        </p>
                        <p className="mt-1 text-sm text-neutral-500 dark:text-neutral-400">
                            Upgrade to Laravel Translations Pro to automatically
                            translate your keys using AI providers.
                        </p>
                    </div>
                </div>
            </DialogContent>
        </Dialog>
    );
}
