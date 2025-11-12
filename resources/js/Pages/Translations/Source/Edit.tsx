import React, { useState } from "react";
import { Head, Link, useForm } from "@inertiajs/react";
import DashboardLayout from "@/Layouts/DashboardLayout";
import { PageProps, Phrase, TranslationFile } from "@/types";
import { Button, Input, Tabs, message, Empty, Typography } from "antd";
import { ChevronRightIcon } from "@heroicons/react/24/outline";
import { XCircleIcon } from "@heroicons/react/20/solid";
import IconKey from "@/Components/Icons/IconKey";
import CountryFlag from "@/Components/Language/Flag";
import IconSpeak from "@/Components/Icons/IconSpeak";
// @ts-ignore
import { useSpeechSynthesis } from 'react-speech-kit';

interface Props extends PageProps {
    phrase: Phrase;
    translation: any;
    source: any;
    similarPhrases: Phrase[];
    files: TranslationFile[];
}

const SourcePhraseEdit: React.FC<Props> = ({
    auth,
    phrase,
    translation,
    source,
    similarPhrases,
    files,
}) => {
    const [componentKey, setComponentKey] = useState(0);
    const { speak, cancel, speaking } = useSpeechSynthesis();

    const { data, setData, patch, processing, errors } = useForm({
        phrase: phrase.value || "",
        note: phrase.note || "",
        file: phrase.translation_file_id || "",
    });

    const translationFiles = files.map((file) => ({
        value: file.id,
        label: `${file.name}.${file.extension}`,
    }));

    const handleSubmit = () => {
        patch(route("ltu.source_translation.update", phrase.uuid), {
            preserveScroll: true,
            onSuccess: () => {
                message.success("Source phrase updated successfully");
                setComponentKey((prev) => prev + 1);
            },
            onError: () => {
                message.error("Failed to update source phrase");
            },
        });
    };

    const tabItems = [
        {
            key: "similar",
            label: "Similar",
            children: similarPhrases.length > 0 ? (
                <div className="space-y-2">
                    {similarPhrases.map((phrase, idx) => (
                        <div key={idx} className="p-3 border rounded hover:bg-gray-50">
                            <div className="font-medium text-sm">{phrase.key}</div>
                            <div className="text-gray-600 text-sm">{phrase.value}</div>
                        </div>
                    ))}
                </div>
            ) : (
                <Empty description="No similar phrases" />
            ),
        },
        {
            key: "history",
            label: "History",
            children: (
                <div className="flex items-center justify-center py-12">
                    <span className="text-sm text-gray-500">Coming soon...</span>
                </div>
            ),
        },
    ];

    return (
        <DashboardLayout
            user={auth.user}
            header={
                <>
                    <div className="flex w-full items-center gap-3 py-2">
                        <Link
                            href={route("ltu.source_translation")}
                            className="flex items-center gap-2 rounded-md border border-transparent bg-gray-50 px-2 py-1 hover:border-blue-400 hover:bg-blue-100"
                        >
                            <div className="h-5 shrink-0">
                                <CountryFlag countryCode={translation.language.code} />
                            </div>
                            <div className="flex items-center space-x-2">
                                <div className="text-sm font-semibold text-gray-600">
                                    {translation.language.name}
                                </div>
                            </div>
                        </Link>
                        <div>
                            <ChevronRightIcon className="size-6 text-gray-400" />
                        </div>
                        <div className="flex items-center gap-2 rounded-md border border-blue-100 bg-blue-50 px-2 py-1 text-blue-500">
                            <IconKey className="size-4" />
                            <span className="text-sm">{phrase.key}</span>
                        </div>
                    </div>

                    <Link
                        href={route("ltu.source_translation")}
                        className="flex size-10 items-center justify-center rounded-full bg-gray-100 p-1 hover:bg-gray-200"
                    >
                        <ChevronRightIcon className="size-6 text-gray-400" />
                    </Link>
                </>
            }
        >
            <Head title="Edit Source Phrase" />

            <div className="mx-auto max-w-7xl px-6 py-10 lg:px-8">
                <div className="flex w-full flex-col gap-8 lg:flex-row">
                    {/* Left: Source Content Editor */}
                    <div className="relative w-full overflow-hidden rounded-md bg-white shadow ring-2 ring-blue-100 focus-within:ring-blue-400">
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

                            {errors.phrase && (
                                <div className="rounded-md border border-red-400 bg-red-50 px-3 py-1">
                                    <div className="flex items-center gap-1">
                                        <XCircleIcon className="size-5 text-red-400" />
                                        <div className="text-sm text-red-700">{errors.phrase}</div>
                                    </div>
                                </div>
                            )}
                        </div>

                        <div className="flex h-80">
                            <Input.TextArea
                                value={data.phrase}
                                onChange={(e) => setData("phrase", e.target.value)}
                                rows={10}
                                className="size-full resize-none rounded-none border-none px-4 py-2.5 focus:outline-none focus:ring-0"
                                placeholder="Enter source content"
                                autoFocus
                            />
                        </div>

                        <div className="flex w-full items-center justify-between gap-4 border-t border-blue-200 px-4 py-1.5">
                            <div className="text-xs text-gray-400">
                                Characters: {data.phrase.length}
                            </div>

                            <button
                                className="flex size-6 items-center justify-center text-gray-400 hover:text-gray-700"
                                onClick={() =>
                                    speaking
                                        ? cancel()
                                        : speak({ text: data.phrase })
                                }
                                title={speaking ? "Stop" : "Listen"}
                            >
                                <IconSpeak className="size-5" />
                            </button>
                        </div>
                    </div>

                    {/* Right: Metadata Panel */}
                    <div className="w-full overflow-hidden rounded-md bg-white shadow ring-2 ring-blue-100">
                        <div className="flex flex-col gap-4 p-4">
                            <div className="space-y-2">
                                <label className="text-sm font-medium text-gray-700">File</label>
                                <select
                                    value={data.file}
                                    onChange={(e) => setData("file", e.target.value)}
                                    className="w-full px-3 py-2 border border-gray-300 rounded-md text-sm"
                                >
                                    <option value="">Select file</option>
                                    {translationFiles.map((file) => (
                                        <option key={file.value} value={file.value}>
                                            {file.label}
                                        </option>
                                    ))}
                                </select>
                                {errors.file && (
                                    <div className="text-xs text-red-600">{errors.file}</div>
                                )}
                            </div>

                            <div className="space-y-2">
                                <label className="text-sm font-medium text-gray-700">
                                    Translation note
                                </label>
                                <Input.TextArea
                                    value={data.note}
                                    onChange={(e) => setData("note", e.target.value)}
                                    rows={3}
                                    className="resize-none"
                                    placeholder="Add a note to this translation"
                                />
                                {errors.note && (
                                    <div className="text-xs text-red-600">{errors.note}</div>
                                )}
                            </div>
                        </div>

                        <div className="grid grid-cols-2 border-t border-blue-200">
                            <Link
                                href={route("ltu.source_translation")}
                                className="flex items-center justify-center bg-blue-50 py-4 text-sm font-medium uppercase text-blue-600 hover:bg-blue-100"
                            >
                                Cancel
                            </Link>

                            <button
                                onClick={handleSubmit}
                                disabled={processing}
                                className="flex items-center justify-center bg-blue-600 py-4 text-sm font-medium uppercase text-white hover:bg-blue-700 disabled:opacity-50"
                            >
                                {processing ? "Saving..." : "Save Translation"}
                            </button>
                        </div>
                    </div>
                </div>

                {/* Tabs Section */}
                <div className="mt-8 bg-white rounded-lg shadow p-6">
                    <Tabs items={tabItems} />
                </div>
            </div>
        </DashboardLayout>
    );
};

export default SourcePhraseEdit;
