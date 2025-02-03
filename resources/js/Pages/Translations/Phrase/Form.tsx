import React, {useState} from "react";
import {Head, useForm} from '@inertiajs/react';
import CountryFlag from "@/Components/Language/Flag";
import {
    Alert,
    Button,
    Empty,
    Form,
    Input,
    message,
    Tabs,
} from "antd"
import DashboardLayout from '@/Layouts/DashboardLayout';
import { PageProps, Phrase, Suggestion, Translation } from "@/types"
import {
    ChevronRightIcon,
    PencilIcon,
    StarIcon
} from "@heroicons/react/24/outline"
import IconTranslate from "@/Components/Icons/IconTranslate"
import IconSimilar from "@/Components/Icons/IconSimilar"
import IconHistory from "@/Components/Icons/IconHistory"
import SourcePhrase from "@/Pages/Translations/Phrase/SourcePhrase"
import PhraseHeader from "@/Pages/Translations/Phrase/Header"
import IconGoogleTranslation from "@/Components/Icons/IconGoogleTranslation"
import IconSpeak from "@/Components/Icons/IconSpeak"
import IconKey from "@/Components/Icons/IconKey"
import RenderPhraseWithParameters
    from "@/Components/Phrase/RenderPhraseWithParameters"

export interface TabItem {
    key: string;
    label: React.ReactNode;
    children: React.ReactNode;
}

export interface Props {
    phrase: Phrase;
    auth: PageProps['auth'];
    translation: Translation;
    similarPhrases: Phrase[];
    suggestedTranslations: Suggestion[];
}

const PhraseForm = ({ auth, translation, phrase, similarPhrases, suggestedTranslations }: Props) => {
    const [componentKey, setComponentKey] = useState(0);

    const { data, setData, put, processing, errors } = useForm({
        phrase: phrase.value || '',
    });

    const tabItems: TabItem[] = [
        {
            key: "suggestions",
            label: (
                <div className="flex items-center gap-2 pl-4">
                    <StarIcon className="size-5" />
                    <span className="text-sm uppercase font-medium">Suggestions</span>
                </div>
            ),
            children: suggestedTranslations.length > 0 ? (
                <div className="flex w-full flex-col divide-y">
                    {suggestedTranslations.map((suggestion: Suggestion, index: number) => (
                        <div className="flex flex-wrap divide-x" key={index}>
                            <div className="px-4 py-4 md:w-64">
                                <div className="flex gap-2 items-center">
                                    <IconGoogleTranslation className="size-4 text-gray-500" />
                                    <div className="text-sm font-medium text-gray-700">
                                        {suggestion.engine}
                                    </div>
                                </div>
                            </div>
                            <div className="flex flex-1 items-center px-4 py-3">
                                <span className="text-gray-700">{suggestion.value}</span>
                            </div>
                            <div className="flex divide-x">
                                <button className="flex w-14 items-center justify-center px-4 py-3 text-gray-400 transition-colors duration-100 hover:bg-blue-100 hover:text-blue-600 v-popper--has-tooltip">
                                    <IconTranslate className="size-6" />
                                </button>
                                <button className="flex w-14 items-center justify-center px-4 py-3 text-gray-400 transition-colors duration-100 hover:bg-blue-100 hover:text-blue-600 v-popper--has-tooltip">
                                    <IconSpeak className="size-6" />
                                </button>
                            </div>
                        </div>
                    ))}

                    <div className="flex py-16 w-full items-center justify-center px-4">
                        <span className="text-sm text-gray-500">More integrations coming in next releases...</span>
                    </div>
                </div>
            ) : (
                <div className="w-full">
                    <Empty
                        className="py-8"
                        image={
                            <IconGoogleTranslation className="size-14 mb-4 text-gray-400" />}
                        description={
                            <div className="flex flex-col justify-center space-y-2 text-base">
                                <span>No suggestions available.</span>
                                <span>There are no suggestions available for this phrase.</span>
                            </div>
                        }
                    >
                    </Empty>
                </div>
            ),
        },
        {
            key: "similar",
            label: (
                <div className="flex items-center gap-2">
                    <IconSimilar className="size-5" />
                    <span className="text-sm uppercase font-medium">Similar</span>
                </div>
            ),
            children: similarPhrases.length > 0 ? (
                <div className="flex w-full flex-col divide-y">
                    {similarPhrases.map((similarPhrase: Phrase, index: number) => (
                        <div className="flex flex-wrap divide-x" key={index}>
                            <div className="px-4 flex items-center py-2 md:w-72">
                                <div className="flex gap-2 border max-w-max px-2 py-0.5 rounded-md items-center">
                                    <IconKey className="size-4 text-gray-500" />
                                    <div className="text-sm font-medium text-gray-500">
                                        {similarPhrase.key}
                                    </div>
                                </div>
                            </div>
                            <div className="flex flex-1 items-center px-4 py-3">
                                {similarPhrase.value ? (
                                    <RenderPhraseWithParameters
                                        value={similarPhrase.value}
                                        parameters={similarPhrase.parameters}
                                    />
                                ) : (
                                    <div className="text-sm text-gray-400">
                                        No translation available
                                    </div>
                                )}
                            </div>
                            <div className="flex divide-x">
                                <button className="flex size-14 items-center justify-center px-4 py-3 text-gray-400 transition-colors duration-100 hover:bg-blue-100 hover:text-blue-600 v-popper--has-tooltip">
                                    <PencilIcon className="size-5" />
                                </button>
                            </div>
                        </div>
                    ))}
                </div>
            ) : (
                <div className="w-full">
                    <Empty
                        className="py-8"
                        image={
                            <IconSimilar className="size-14 mb-4 text-gray-400" />}
                        description={
                            <div className="flex flex-col justify-center space-y-2 text-base">
                                <span>No similar translations.</span>
                                <span>We couldn't find any similar translations in this phrase.</span>
                            </div>
                        }
                    >
                    </Empty>
                </div>
            ),
        },
        {
            key: "versions",
            label: (
                <div className="flex items-center gap-2">
                    <IconHistory className="size-5" />
                    <span className="text-sm uppercase font-medium">Versions</span>
                </div>
            ),
            children: (
                <div className="w-full">
                    <Empty
                        className="py-8"
                        image={
                            <IconHistory className="size-14 mb-4 text-gray-400" />}
                        description={
                            <div className="flex flex-col justify-center space-y-2 text-base">
                                <span>There are no previous translations.</span>
                                <span className="font-medium text-gray-600">Be the first one!</span>
                            </div>
                        }
                    >
                    </Empty>
                </div>
            )
        },
        {
            key: "languages",
            label: (
                <div className="flex items-center gap-2">
                    <IconTranslate className="size-5" />
                    <span className="text-sm uppercase font-medium">Languages</span>
                </div>
            ),
            children: (
                <div className="w-full">
                    <Empty
                        className="py-8"
                        image={
                            <IconTranslate className="size-14 mb-4 text-gray-400" />}
                        description={
                            <div className="flex flex-col justify-center space-y-2 text-base">
                                <span>This string was not translated into any other languages</span>
                                <span className="font-medium text-gray-600">Be the first one!</span>
                            </div>
                        }
                    >
                    </Empty>
                </div>
            )
        }
    ];

    const submit = () => {
        put(route("ltu.phrases.update", [translation.id, phrase.uuid]), {
            preserveScroll: true,
            onSuccess: () => {
                setData("phrase", "")
                setComponentKey(prevKey => prevKey + 1)
                message.success("Translation updated successfully").then()
            },
            onError: () => {
                message.error("Failed to update translation").then()
            },
        })
    }

    return (
        <DashboardLayout user={auth.user} header={<PhraseHeader translation={translation} phrase={phrase} />}>

            <Head title="Translate" />

            <div className="flex w-full flex-col lg:flex-row">

                <SourcePhrase
                    phrase={phrase.source}
                    translation={phrase.source.translation}
                />

                <div className="flex h-16 w-full items-center justify-center lg:h-auto lg:w-32">
                    <ChevronRightIcon className="size-8 rotate-90 text-blue-200 lg:rotate-0" />
                </div>

                <Form
                    name="basic"
                    layout="vertical"
                    key={componentKey}
                    initialValues={data}
                    onFieldsChange={(changedFields) => {
                        changedFields.forEach(item => {
                            setData(item.name[0], item.value)
                        })
                    }}
                    onFinish={submit}
                    autoComplete="off"
                    className={`w-full overflow-hidden rounded-md bg-white shadow ring-2 ${ errors.phrase ? 'ring-red-100 focus-within:ring-red-400' : 'ring-blue-100 focus-within:ring-blue-400'}`}
                >
                    <div className="flex items-center justify-between border-b px-4">
                        <div className="flex gap-2 py-2.5">
                            <div className="h-5 shrink-0">
                                <CountryFlag countryCode={translation.language.code} />
                            </div>

                            <div className="flex items-center space-x-2">
                                <div className="text-sm font-semibold text-gray-800">
                                    {translation.language.name}
                                </div>

                                <div className="rounded-md border px-1.5 py-0.5 text-xs text-gray-500">
                                    {translation.language.code}
                                </div>
                            </div>
                        </div>

                        {errors && errors.phrase && (
                            <div className="flex py-1.5">
                                <Alert className="py-[2px]" message={errors.phrase} type="error" showIcon />
                            </div>
                        )}
                    </div>

                    <div className="flex flex-col">
                        <Form.Item className="w-full mb-0" name="phrase">
                            <Input.TextArea
                                rows={7}
                                autoFocus
                                size="large"
                                className="w-full !resize-none rounded-none border-none focus:outline-none focus:ring-0 focus-visible:outline-none focus-visible:ring-0"
                            />
                        </Form.Item>
                    </div>

                    <div className="grid border-t border-blue-100 grid-cols-2">
                        <Button
                            href="#"
                            size="large"
                            disabled={processing}
                            className="rounded-none h-12 border-none text-gray-400 border-gray-200 py-6"
                        >
                            Cancel
                        </Button>

                        <Button
                            size="large"
                            type="primary"
                            htmlType="submit"
                            loading={processing}
                            className="rounded-none h-12 border-none py-6"
                        >
                            <span className="flex w-auto">Save</span>

                            <span className="hidden md:flex">Translation</span>
                        </Button>
                    </div>
                </Form>
            </div>

            <div className="mt-8 bg-white shadow rounded-lg">
                <Tabs
                    size="large"
                    items={tabItems}
                />
            </div>
        </DashboardLayout>
    );
}

export default PhraseForm;