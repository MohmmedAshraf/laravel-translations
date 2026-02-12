import { Head, router, useForm, usePage } from '@inertiajs/react';
import { useCallback, useRef } from 'react';
import { EditorFooter } from '@/components/translations/editor-footer';
import { EditorHeader } from '@/components/translations/editor-header';
import { LanguageBar } from '@/components/translations/language-bar';
import { ParameterBadges } from '@/components/translations/parameter-badges';
import TranslationKeySidebar from '@/components/translations/translation-key-sidebar';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Separator } from '@/components/ui/separator';
import { Textarea } from '@/components/ui/textarea';
import { useEditorShortcuts } from '@/hooks/use-editor-shortcuts';
import AppLayout from '@/layouts/app-layout';
import { Notebook } from '@/lib/icons';
import { index as languagesIndex } from '@/routes/ltu/languages';
import { index as sourceIndex, show, update } from '@/routes/ltu/source';
import type { BreadcrumbItem, Group, Language, TranslationKey } from '@/types';

interface PageProps {
    sourceLanguage: Language;
    translationKey: TranslationKey;
    groups: Group[];
    previousKey: number | null;
    nextKey: number | null;
    [key: string]: unknown;
}

export default function Show() {
    const { sourceLanguage, translationKey, groups, previousKey, nextKey } =
        usePage<PageProps>().props;

    const fullKey = `${translationKey.group.name}.${translationKey.key}`;

    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Translations', href: languagesIndex().url },
        { title: 'Source Language', href: sourceIndex().url },
        { title: fullKey, href: '#' },
    ];

    const { data, setData, put, processing, recentlySuccessful, errors } =
        useForm({
            key: translationKey.key,
            group_id: String(translationKey.group.id),
            value: translationKey.translations[0]?.value ?? '',
            context_note: translationKey.context_note ?? '',
            priority: translationKey.priority ?? 'low',
        });

    const formRef = useRef<HTMLFormElement>(null);

    function handleSubmit(e: React.FormEvent): void {
        e.preventDefault();
        put(update.url({ translationKey: translationKey.id }), {
            preserveScroll: true,
        });
    }

    const previousHref = previousKey
        ? show.url({ translationKey: previousKey })
        : null;
    const nextHref = nextKey ? show.url({ translationKey: nextKey }) : null;
    const backHref = sourceIndex().url;

    const handleSave = useCallback(() => formRef.current?.requestSubmit(), []);
    const handlePrevious = useCallback(
        () => previousHref && router.visit(previousHref),
        [previousHref],
    );
    const handleNext = useCallback(
        () => nextHref && router.visit(nextHref),
        [nextHref],
    );

    useEditorShortcuts({
        onSave: handleSave,
        onPrevious: previousHref ? handlePrevious : undefined,
        onNext: nextHref ? handleNext : undefined,
    });

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`${fullKey} - Source Language`} />

            <div className="flex h-full flex-1 overflow-hidden">
                {/* Main content */}
                <div className="flex min-w-0 flex-1 flex-col overflow-hidden">
                    <EditorHeader
                        translationKey={translationKey}
                        previousHref={previousHref}
                        nextHref={nextHref}
                        backHref={backHref}
                    />

                    {/* Editor */}
                    <form
                        ref={formRef}
                        onSubmit={handleSubmit}
                        className="flex min-h-0 flex-1 flex-col overflow-hidden"
                    >
                        {/* Key + Group + Priority bar */}
                        <div className="flex shrink-0 items-center gap-3 border-b px-4 py-2.5">
                            <Label
                                htmlFor="key"
                                className="shrink-0 text-sm text-muted-foreground"
                            >
                                Key
                            </Label>
                            <Input
                                id="key"
                                value={data.key}
                                onChange={(e) => setData('key', e.target.value)}
                                className="h-8 min-w-0 flex-1 font-mono text-sm"
                                required
                            />
                            {errors.key && (
                                <p className="shrink-0 text-sm text-destructive">
                                    {errors.key}
                                </p>
                            )}
                            <Separator orientation="vertical" className="h-5" />
                            <Label
                                htmlFor="group"
                                className="shrink-0 text-sm text-muted-foreground"
                            >
                                Group
                            </Label>
                            <Select
                                value={data.group_id}
                                onValueChange={(v) => setData('group_id', v)}
                            >
                                <SelectTrigger
                                    id="group"
                                    className="h-8 w-32 text-sm"
                                >
                                    <SelectValue />
                                </SelectTrigger>
                                <SelectContent>
                                    {groups.map((group) => (
                                        <SelectItem
                                            key={group.id}
                                            value={String(group.id)}
                                        >
                                            {group.name}
                                        </SelectItem>
                                    ))}
                                </SelectContent>
                            </Select>
                            <Separator orientation="vertical" className="h-5" />
                            <Label
                                htmlFor="priority"
                                className="shrink-0 text-sm text-muted-foreground"
                            >
                                Priority
                            </Label>
                            <Select
                                value={data.priority}
                                onValueChange={(v) => setData('priority', v)}
                            >
                                <SelectTrigger
                                    id="priority"
                                    className="h-8 w-28 text-sm"
                                >
                                    <SelectValue />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="low">Low</SelectItem>
                                    <SelectItem value="medium">
                                        Medium
                                    </SelectItem>
                                    <SelectItem value="high">High</SelectItem>
                                    <SelectItem value="critical">
                                        Critical
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>

                        {/* Source value */}
                        <div className="flex min-h-0 flex-1 flex-col overflow-hidden">
                            <LanguageBar language={sourceLanguage}>
                                {data.value && (
                                    <span className="text-xs text-muted-foreground tabular-nums">
                                        {data.value.length} chars
                                    </span>
                                )}
                            </LanguageBar>

                            <div className="flex flex-1 flex-col overflow-hidden p-4">
                                <Textarea
                                    id="value"
                                    className="flex-1 resize-none border-0 p-0 text-sm shadow-none focus-visible:ring-0"
                                    value={data.value}
                                    onChange={(e) =>
                                        setData('value', e.target.value)
                                    }
                                    placeholder={`Enter ${sourceLanguage.name} source value...`}
                                    rows={6}
                                />
                                {errors.value && (
                                    <p className="mt-2 text-sm text-destructive">
                                        {errors.value}
                                    </p>
                                )}
                            </div>

                            <ParameterBadges
                                parameters={translationKey.parameters}
                            />
                        </div>

                        {/* Context note */}
                        <div className="shrink-0 border-t">
                            <div className="flex items-center gap-2 px-4 pt-3 pb-1.5">
                                <Notebook className="size-4 shrink-0 text-muted-foreground" />
                                <span className="text-sm font-medium text-muted-foreground">
                                    Context Note
                                </span>
                            </div>
                            <div className="px-4 pb-3">
                                <Textarea
                                    id="context_note"
                                    value={data.context_note}
                                    onChange={(e) =>
                                        setData('context_note', e.target.value)
                                    }
                                    rows={7}
                                    className="resize-none text-sm"
                                    placeholder="Provide context to help translators and AI understand this phrase"
                                />
                                {errors.context_note && (
                                    <p className="mt-1 text-sm text-destructive">
                                        {errors.context_note}
                                    </p>
                                )}
                            </div>
                        </div>

                        {/* Footer */}
                        <EditorFooter
                            backHref={backHref}
                            processing={processing}
                            recentlySuccessful={recentlySuccessful}
                            onSubmit={handleSave}
                            submitLabel="Save Changes"
                            size="lg"
                        />
                    </form>
                </div>

                {/* Sidebar */}
                <TranslationKeySidebar
                    translationKey={translationKey}
                    showPrioritySelect
                />
            </div>
        </AppLayout>
    );
}
