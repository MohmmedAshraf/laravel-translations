export type Language = {
    id: number
    name: string
    code: string
    rtl: boolean
}

export type Role = {
    value: string
    label: string
}

export type NotificationType = "success" | "error" | "warning" | "info" | "default"

export type Notification = {
    type: NotificationType
    body: string
}

export type MachineTranslations = {
    id: number
    value: string
    engine: string
}

export type Contributor = {
    id: number
    name: string
    email: string
    role: Role
    created_at: string
    updated_at: string
}

export type Invite = {
    id: number
    email: string
    role: Role
    invited_at: string
}

export type SourcePhrase = {
    id: number
    uuid: string
    key: string
    group: string
    value: string
    parameters: Array<string>
    created_at: string
    updated_at: string
    state: boolean
    note: string
    value_html: Array<{
        parameter: boolean
        value: string
    }>
    translation_id: number
    translation_file_id: number
    file: TranslationFile
}

export type Phrase = {
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
    file: TranslationFile
}

export type SourceTranslation = {
    id: number
    language: Language
    progress: number
    phrases_count: number
    created_at: string
    updated_at: string
}

export type Translation = {
    id: number
    language: Language
    source_translation: SourceTranslation
    progress: number
    source: boolean
    phrases_count: number
    created_at: string
    updated_at: string
}

export type TranslationFile = {
    id: number
    name: string
    extension: string
    nameWithExtension: string
}
