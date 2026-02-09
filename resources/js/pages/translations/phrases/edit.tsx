import { Head, router, useForm, usePage } from '@inertiajs/react';
import { useCallback, useRef } from 'react';
import { EditorFooter } from '@/components/translations/editor-footer';
import { EditorHeader } from '@/components/translations/editor-header';
import { LanguageBar } from '@/components/translations/language-bar';
import { SourcePanel } from '@/components/translations/source-panel';
import TranslationKeySidebar from '@/components/translations/translation-key-sidebar';
import { ValidationIssuesAlert } from '@/components/translations/validation-issues-alert';
import { Separator } from '@/components/ui/separator';
import { Textarea } from '@/components/ui/textarea';
import { useEditorShortcuts } from '@/hooks/use-editor-shortcuts';
import AppLayout from '@/layouts/app-layout';
import { AltArrowRight } from '@/lib/icons';
import { index as languagesIndex } from '@/routes/ltu/languages';
import { edit, index as phrasesIndex, update } from '@/routes/ltu/phrases';
import type {
    BreadcrumbItem,
    Language,
    SourceTranslation,
    TranslationKey,
} from '@/types';

export interface QualityIssue {
    check: string;
    checkLabel: string;
    severity: 'error' | 'warning' | 'info';
    message: string;
    suggestion: string | null;
    autoFixable: boolean;
}

export interface QualityCheck {
    check: string;
    label: string;
}

interface PageProps {
    language: Language;
    sourceLanguage: Language | null;
    sourceTranslation: SourceTranslation | null;
    translationKey: TranslationKey;
    translationIsEmpty: boolean;
    qualityIssues?: QualityIssue[];
    qualityChecks?: QualityCheck[];
    previousKey: number | null;
    nextKey: number | null;
    [key: string]: unknown;
}

export default function EditPhrase() {
    const {
        language,
        sourceLanguage,
        sourceTranslation,
        translationKey,
        translationIsEmpty,
        qualityIssues,
        qualityChecks,
        previousKey,
        nextKey,
    } = usePage<PageProps>().props;

    const currentTranslation = translationKey.translations[0];
    const currentStatus = currentTranslation?.status ?? 'untranslated';
    const sourceText = sourceTranslation?.value ?? '';

    const breadcrumbs: BreadcrumbItem[] = [
        { title: 'Translations', href: languagesIndex().url },
        {
            title: language.name,
            href: phrasesIndex.url({ language: language.id }),
        },
        { title: `Edit "${translationKey.key}"`, href: '#' },
    ];

    const { data, setData, put, processing, recentlySuccessful, errors } =
        useForm({
            value: currentTranslation?.value ?? '',
        });

    const formRef = useRef<HTMLFormElement>(null);

    function handleSubmit(e: React.FormEvent): void {
        e.preventDefault();
        put(
            update.url({
                language: language.id,
                translationKey: translationKey.id,
            }),
            { preserveScroll: true },
        );
    }

    const previousHref = previousKey
        ? edit.url({ language: language.id, translationKey: previousKey })
        : null;
    const nextHref = nextKey
        ? edit.url({ language: language.id, translationKey: nextKey })
        : null;
    const backHref = phrasesIndex.url({ language: language.id });

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
            <Head title={`Edit "${translationKey.key}"`} />

            <div className="flex h-full flex-1 overflow-hidden">
                <div className="flex min-w-0 flex-1 flex-col overflow-hidden">
                    <EditorHeader
                        translationKey={translationKey}
                        status={currentStatus}
                        previousHref={previousHref}
                        nextHref={nextHref}
                        backHref={backHref}
                    />

                    <div
                        className="grid shrink-0 border-b lg:grid-cols-[1fr_auto_1fr]"
                        style={{ maxHeight: '50vh' }}
                    >
                        <SourcePanel
                            language={sourceLanguage}
                            sourceText={sourceText}
                            contextNote={translationKey.context_note}
                            parameters={translationKey.parameters}
                            translationKeyId={translationKey.id}
                            onCopyToTranslation={() =>
                                setData('value', sourceText)
                            }
                        />

                        <div className="hidden items-center border-x bg-muted/30 px-1.5 lg:flex">
                            <AltArrowRight className="size-4 text-muted-foreground/40" />
                        </div>
                        <Separator className="lg:hidden" />

                        <div className="flex flex-col overflow-hidden">
                            <LanguageBar language={language} />

                            <div className="relative flex flex-1 flex-col overflow-hidden p-4">
                                <form
                                    ref={formRef}
                                    onSubmit={handleSubmit}
                                    className="flex flex-1 flex-col"
                                >
                                    <Textarea
                                        id="value"
                                        className="flex-1 resize-none border-0 p-0 text-sm shadow-none focus-visible:ring-0"
                                        dir={language.rtl ? 'rtl' : undefined}
                                        value={data.value}
                                        onChange={(e) =>
                                            setData('value', e.target.value)
                                        }
                                        placeholder={`Enter ${language.name} translation...`}
                                        rows={6}
                                    />
                                </form>

                                <ValidationIssuesAlert
                                    formError={errors.value}
                                    qualityIssues={qualityIssues}
                                />
                            </div>

                            <EditorFooter
                                backHref={backHref}
                                processing={processing}
                                recentlySuccessful={recentlySuccessful}
                                onSubmit={handleSave}
                                submitLabel="Save Translation"
                            />
                        </div>
                    </div>

                    <div className="flex min-h-0 flex-1 items-center justify-center text-sm text-muted-foreground">
                        <p>
                            Upgrade to Pro for AI translation, glossary,
                            comments, and more.
                        </p>
                    </div>
                </div>

                <TranslationKeySidebar
                    translationKey={translationKey}
                    status={currentStatus}
                    qualityIssues={qualityIssues}
                    qualityChecks={qualityChecks}
                    translationIsEmpty={translationIsEmpty}
                />
            </div>
        </AppLayout>
    );
}
