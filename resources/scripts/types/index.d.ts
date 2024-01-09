export interface Language {
    id: number;
    name: string;
    code: string;
    rtl: boolean;
}

export interface SourcePhrase {
    id: number
    uuid: string
    key: string
    group: string
    value: string
    parameters: Array<string>
    created_at: string
    updated_at: string
    state: boolean
    value_html: Array<{
        parameter: boolean
        value: string
    }>
    translation_id: number
    translation_file_id: number
}

export interface Phrase {
    id: number
    uuid: string
    key: string
    group: string
    value: any
    parameters: Array<string>
    created_at: string
    updated_at: string
    state: boolean
    value_html: Array<{
        parameter: boolean
        value: string
    }>
    translation_id: number
    translation_file_id: number
    phrase_id: number
    source: SourcePhrase
}

export interface SourceTranslation {
    id: number;
    language: Language;
    progress: number;
    phrases_count: number;
    created_at: string;
    updated_at: string;
}

export interface Translation {
    id: number;
    language: Language;
    source_language: SourceTranslation;
    progress: number;
    source: boolean;
    phrases_count: number;
    created_at: string;
    updated_at: string;
}

export interface TranslationFile {
    id: number;
    name: string;
    extension: string;
}
