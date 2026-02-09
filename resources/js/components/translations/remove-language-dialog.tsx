import { useForm } from '@inertiajs/react';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Flag } from '@/components/ui/flag';
import { destroy } from '@/routes/ltu/languages';

export interface RemoveLanguageItem {
    id: number;
    code: string;
    name: string;
    translated_count: number;
    needs_review_count: number;
}

interface RemoveLanguageDialogProps {
    language: RemoveLanguageItem;
    open: boolean;
    onOpenChange: (open: boolean) => void;
}

export function RemoveLanguageDialog({
    language,
    open,
    onOpenChange,
}: RemoveLanguageDialogProps) {
    const {
        processing,
        data,
        setData,
        delete: deleteRequest,
        reset,
    } = useForm({
        delete_translations: false,
    });

    const handleSubmit = (e: React.FormEvent) => {
        e.preventDefault();
        deleteRequest(destroy.url({ language: language.id }), {
            onSuccess: () => {
                reset();
                onOpenChange(false);
            },
        });
    };

    const totalTranslations =
        language.translated_count + language.needs_review_count;

    return (
        <Dialog open={open} onOpenChange={onOpenChange}>
            <DialogContent className="sm:max-w-md">
                <DialogHeader>
                    <DialogTitle>Remove language</DialogTitle>
                    <DialogDescription className="sr-only">
                        Remove {language.name} from your project.
                    </DialogDescription>
                </DialogHeader>

                <form onSubmit={handleSubmit} className="space-y-5">
                    <div className="flex items-center gap-3 rounded-lg bg-neutral-50 px-4 py-3 dark:bg-neutral-800/50">
                        <Flag
                            code={language.code}
                            className="size-8 shrink-0"
                        />
                        <div className="min-w-0">
                            <p className="font-medium text-neutral-900 dark:text-neutral-100">
                                {language.name}
                            </p>
                            <p className="text-xs text-neutral-500 dark:text-neutral-400">
                                {totalTranslations > 0
                                    ? `${totalTranslations} translations`
                                    : 'No translations yet'}
                                {' · '}
                                <Badge
                                    variant="outline"
                                    className="px-1 py-0 font-mono text-[10px]"
                                >
                                    {language.code}
                                </Badge>
                            </p>
                        </div>
                    </div>

                    <p className="text-sm text-neutral-600 dark:text-neutral-400">
                        This will deactivate the language and hide it from your
                        project. You can re-add it later.
                    </p>

                    {totalTranslations > 0 && (
                        <label className="flex cursor-pointer items-center gap-3">
                            <Checkbox
                                checked={data.delete_translations}
                                onCheckedChange={(checked) =>
                                    setData(
                                        'delete_translations',
                                        checked === true,
                                    )
                                }
                            />
                            <span className="text-sm text-neutral-700 dark:text-neutral-300">
                                Also delete {totalTranslations} translations
                            </span>
                        </label>
                    )}

                    <div className="grid grid-cols-5 gap-3">
                        <Button
                            type="button"
                            variant="outline"
                            className="col-span-2"
                            onClick={() => onOpenChange(false)}
                        >
                            Cancel
                        </Button>
                        <Button
                            type="submit"
                            variant="destructive"
                            className="col-span-3"
                            disabled={processing}
                        >
                            {processing ? 'Removing...' : 'Remove Language'}
                        </Button>
                    </div>
                </form>
            </DialogContent>
        </Dialog>
    );
}
