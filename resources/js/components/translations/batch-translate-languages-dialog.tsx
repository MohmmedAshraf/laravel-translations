import { ProFeatureDialog } from '@/components/translations/pro-feature-dialog';

interface LanguageInput {
    code: string;
    name: string;
}

interface BatchTranslateLanguagesDialogProps {
    languages: LanguageInput[];
    open: boolean;
    onOpenChange: (open: boolean) => void;
    onComplete: () => void;
}

export function BatchTranslateLanguagesDialog({
    open,
    onOpenChange,
}: BatchTranslateLanguagesDialogProps) {
    return (
        <ProFeatureDialog
            title="AI Translate Languages"
            description="Translate all untranslated keys using AI."
            open={open}
            onOpenChange={onOpenChange}
        />
    );
}
