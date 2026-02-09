export type Group = {
    id: number;
    name: string;
    namespace: string | null;
};

export type Language = {
    id: number;
    code: string;
    name: string;
    rtl: boolean;
};

export type LanguageWithStats = Language & {
    native_name: string | null;
    is_source: boolean;
    active: boolean;
    progress: number;
    translated_count: number;
    untranslated_count: number;
    needs_review_count: number;
    total_keys: number;
    last_activity: string | null;
    last_activity_at: string | null;
};

export type GroupWithKeys = Group & {
    file_format: string;
    translation_keys_count: number;
};

export type Translation = {
    id: number;
    value: string | null;
    status: string;
    needs_review?: boolean;
    reviewer_feedback?: string | null;
};

export type TranslationKey = {
    id: number;
    key: string;
    context_note: string | null;
    parameters: string[] | null;
    is_html: boolean;
    is_plural: boolean;
    priority: string | null;
    group: Group;
    translations: Translation[];
    created_at?: string;
    updated_at?: string;
};

export type SourceTranslation = {
    id: number;
    value: string | null;
};

export type TranslationComment = {
    id: number;
    userName: string;
    userInitials: string;
    body: string;
    type: 'comment' | 'approval' | 'rejection' | 'review_request';
    createdAt: string;
};

export const statusColors: Record<
    string,
    'default' | 'secondary' | 'destructive' | 'outline'
> = {
    untranslated: 'secondary',
    translated: 'default',
    needs_review: 'destructive',
    approved: 'outline',
};

export const statusLabels: Record<string, string> = {
    untranslated: 'Untranslated',
    translated: 'Translated',
    needs_review: 'Needs Review',
    approved: 'Approved',
};
