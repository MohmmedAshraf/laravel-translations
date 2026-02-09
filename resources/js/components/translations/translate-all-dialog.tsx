import { ProFeatureDialog } from '@/components/translations/pro-feature-dialog';

interface TranslateAllDialogProps {
    locale: string;
    languageName: string;
    open: boolean;
    onOpenChange: (open: boolean) => void;
    onComplete: () => void;
}

export function TranslateAllDialog({
    open,
    onOpenChange,
}: TranslateAllDialogProps) {
    return (
        <ProFeatureDialog
            title="Translate All Missing"
            description="Translate all untranslated keys using AI."
            open={open}
            onOpenChange={onOpenChange}
        />
    );
}
