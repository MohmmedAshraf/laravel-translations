import { useRef, useState } from 'react';
import { toast } from 'sonner';
import HighlightedText from '@/components/translations/highlighted-text';
import { LanguageBar } from '@/components/translations/language-bar';
import { ParameterBadges } from '@/components/translations/parameter-badges';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import { Textarea } from '@/components/ui/textarea';
import {
    Tooltip,
    TooltipContent,
    TooltipTrigger,
} from '@/components/ui/tooltip';
import { apiPost } from '@/lib/api';
import { ClipboardAdd, ClipboardCheck, Notebook, Pen2 } from '@/lib/icons';
import { update as updateSource } from '@/routes/ltu/source';
import type { Language } from '@/types';

interface SourcePanelProps {
    language: Language | null;
    sourceText: string;
    contextNote: string | null;
    parameters: string[] | null;
    translationKeyId: number;
    onCopyToTranslation: () => void;
}

export function SourcePanel({
    language,
    sourceText,
    contextNote,
    parameters,
    translationKeyId,
    onCopyToTranslation,
}: SourcePanelProps) {
    const [copied, setCopied] = useState(false);
    const [editing, setEditing] = useState(false);
    const [note, setNote] = useState(contextNote ?? '');
    const [saving, setSaving] = useState(false);
    const [savedNote, setSavedNote] = useState(contextNote);
    const textareaRef = useRef<HTMLTextAreaElement>(null);

    function handleCopy(): void {
        onCopyToTranslation();
        setCopied(true);
        setTimeout(() => setCopied(false), 1500);
    }

    function handleEdit(): void {
        setEditing(true);
        setTimeout(() => textareaRef.current?.focus(), 0);
    }

    function handleCancel(): void {
        setNote(savedNote ?? '');
        setEditing(false);
    }

    function handleSave(): void {
        setSaving(true);
        apiPost(updateSource.url(translationKeyId), {
            _method: 'PUT',
            context_note: note || null,
        })
            .then(() => {
                setSavedNote(note || null);
                setEditing(false);
                toast.success('Context note saved');
            })
            .catch(() => {
                toast.error('Failed to save context note');
            })
            .finally(() => setSaving(false));
    }

    return (
        <div className="flex flex-col overflow-hidden">
            <LanguageBar
                language={
                    language ?? { id: 0, code: '', name: 'Source', rtl: false }
                }
            >
                <Tooltip>
                    <TooltipTrigger asChild>
                        <Button
                            variant="ghost"
                            size="sm"
                            className="h-7 gap-1.5"
                            onClick={handleCopy}
                            disabled={!sourceText}
                        >
                            {copied ? (
                                <ClipboardCheck className="size-4" />
                            ) : (
                                <ClipboardAdd className="size-4" />
                            )}
                            <span className="text-xs">
                                {copied ? 'Copied' : 'Copy'}
                            </span>
                        </Button>
                    </TooltipTrigger>
                    <TooltipContent>Copy source to translation</TooltipContent>
                </Tooltip>
            </LanguageBar>

            <div className="flex-1 overflow-y-auto p-4">
                <p className="text-sm leading-relaxed whitespace-pre-wrap">
                    {sourceText ? (
                        <HighlightedText text={sourceText} />
                    ) : (
                        <span className="text-muted-foreground italic">
                            No source text
                        </span>
                    )}
                </p>
            </div>

            <ParameterBadges parameters={parameters} />

            {editing ? (
                <div className="shrink-0 border-t p-3">
                    <div className="mb-2 flex items-center gap-2">
                        <Notebook className="size-4 shrink-0 text-muted-foreground" />
                        <span className="text-xs font-medium text-muted-foreground">
                            Context Note
                        </span>
                    </div>
                    <Textarea
                        ref={textareaRef}
                        value={note}
                        onChange={(e) => setNote(e.target.value)}
                        rows={3}
                        className="resize-none text-sm"
                        placeholder="Add context to help translators understand this phrase..."
                    />
                    <div className="mt-2 flex items-center justify-end gap-2">
                        <Button
                            variant="ghost"
                            size="sm"
                            onClick={handleCancel}
                            disabled={saving}
                        >
                            Cancel
                        </Button>
                        <Button
                            size="sm"
                            onClick={handleSave}
                            disabled={saving}
                        >
                            {saving && <Spinner className="size-3.5" />}
                            Save Note
                        </Button>
                    </div>
                </div>
            ) : (
                <button
                    type="button"
                    onClick={handleEdit}
                    className="group flex h-11 shrink-0 items-center gap-2 border-t px-4 text-muted-foreground transition-colors hover:bg-muted/50"
                >
                    <Notebook className="size-4 shrink-0" />
                    <p className="flex-1 truncate text-left text-sm">
                        {savedNote || 'No translation note'}
                    </p>
                    <Pen2 className="size-3.5 shrink-0 opacity-0 transition-opacity group-hover:opacity-100" />
                </button>
            )}
        </div>
    );
}
