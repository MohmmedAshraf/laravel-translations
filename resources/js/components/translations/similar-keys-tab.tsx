import { Key, Languages, UndoLeftRound } from '@/lib/icons';
import type { SimilarKey } from '@/types';

function SimilarKeyRow({
    keyName,
    source,
    translation,
    onUse,
}: {
    keyName: string;
    source: string | null;
    translation: string | null;
    onUse: () => void;
}) {
    return (
        <button
            type="button"
            onClick={onUse}
            disabled={!translation}
            className="group flex w-full items-center gap-3 border-b px-4 py-2.5 text-left transition-colors last:border-b-0 hover:bg-accent/50 disabled:cursor-default disabled:hover:bg-transparent"
        >
            <span className="flex shrink-0 items-center gap-1.5 rounded-md bg-muted px-2 py-1 font-mono text-xs text-muted-foreground">
                <Languages className="size-3.5" />
                {keyName}
            </span>
            <span className="min-w-0 flex-1 truncate text-sm text-muted-foreground">
                {source ?? '—'}
            </span>
            {translation ? (
                <>
                    <span className="min-w-0 flex-1 truncate text-sm">
                        {translation}
                    </span>
                    <span className="flex shrink-0 items-center gap-1 rounded-md border bg-background px-2 py-1 text-xs text-muted-foreground opacity-0 shadow-sm transition-opacity group-hover:opacity-100">
                        <UndoLeftRound className="size-4" />
                        Use
                    </span>
                </>
            ) : (
                <span className="min-w-0 flex-1 truncate text-sm text-muted-foreground/50 italic">
                    Not translated
                </span>
            )}
        </button>
    );
}

interface SimilarKeysTabProps {
    similarKeys: SimilarKey[];
    onUseSuggestion: (translation: string) => void;
}

export function SimilarKeysTab({
    similarKeys,
    onUseSuggestion,
}: SimilarKeysTabProps) {
    if (similarKeys.length === 0) {
        return (
            <div className="flex flex-1 flex-col items-center justify-center gap-3 p-8 text-center">
                <div className="flex size-10 items-center justify-center rounded-full bg-muted">
                    <Key className="size-5 text-muted-foreground/60" />
                </div>
                <div>
                    <p className="text-sm font-medium">No similar keys</p>
                    <p className="mt-1 text-xs text-muted-foreground">
                        Keys with similar names in the same group will appear
                        here for reference.
                    </p>
                </div>
            </div>
        );
    }

    return (
        <>
            <div className="flex items-center border-b bg-muted/20 px-4 py-3">
                <span className="text-sm text-muted-foreground">
                    {similarKeys.length} similar{' '}
                    {similarKeys.length === 1 ? 'key' : 'keys'} found
                </span>
            </div>
            <div>
                {similarKeys.map((item) => (
                    <SimilarKeyRow
                        key={item.id}
                        keyName={item.key}
                        source={item.source}
                        translation={item.translation}
                        onUse={() =>
                            item.translation &&
                            onUseSuggestion(item.translation)
                        }
                    />
                ))}
            </div>
        </>
    );
}
