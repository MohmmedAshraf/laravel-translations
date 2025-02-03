export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string;
}

export interface Language {
    id: number;
    name: string;
    code: string;
    rtl: boolean;
}

export interface Phrase
{
    id: number;
    uuid: string;
    key: string;
    group: string;
    value: string;
    parameters: string[];
    state: boolean;
    note: string;
    value_html: string;
    phrase_id: string;
    file: TranslationFile;
    translation: Translation;
    source: Phrase;
}

export interface Meta
{
    total: number;
    per_page: number;
    current_page: number;
    from: number;
    last_page: number;
    links: {
        url: string | null;
        label: string;
        active: boolean;
    }[];
}

export interface PhrasePagination
{
    data: Phrase[];
    meta: Meta;
    links: {
        first: string;
        last: string;
        prev: string | null;
        next: string | null;
    };
}

export interface Translation {
    id: number;
    source: boolean;
    progress: number;
    language: Language;
    phrases_count: number;
}

export interface Suggestion {
    id: number;
    engine: string;
    value: string;
}

export interface TranslationFile {
    id: number;
    name: string;
    extension: string;
    name_with_extension: string;
    phrases: Phrase[];
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    auth: {
        user: User;
    };
    canPublish: boolean;
    isProductionEnvironment: boolean;
};
