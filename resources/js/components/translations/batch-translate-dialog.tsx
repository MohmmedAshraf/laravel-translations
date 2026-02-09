import { ProFeatureDialog } from '@/components/translations/pro-feature-dialog';

interface BatchTranslateDialogProps {
    keyIds: number[];
    targetLocale: string;
    open: boolean;
    onOpenChange: (open: boolean) => void;
    onComplete: () => void;
}

export function BatchTranslateDialog({
    open,
    onOpenChange,
}: BatchTranslateDialogProps) {
    return (
        <ProFeatureDialog
            title="AI Translate"
            description="Translate selected keys using AI."
            open={open}
            onOpenChange={onOpenChange}
        />
    );
}
