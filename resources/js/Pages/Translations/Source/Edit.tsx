import React, { useState } from "react";
import { Head, Link, useForm } from "@inertiajs/react";
import DashboardLayout from "@/Layouts/DashboardLayout";
import { PageProps, Phrase, TranslationFile } from "@/types";
import { Button, Form, Input, Select, message } from "antd";
import { ChevronRightIcon } from "@heroicons/react/24/outline";
import IconKey from "@/Components/Icons/IconKey";
import CountryFlag from "@/Components/Language/Flag";

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

            <div className="mx-auto max-w-2xl py-8">
                <div className="bg-white rounded-lg shadow p-6">
                    <Form
                        key={componentKey}
                        layout="vertical"
                        initialValues={data}
                        onFieldsChange={(changedFields) => {
                            changedFields.forEach((item) => {
                                setData(item.name[0] as string, item.value);
                            });
                        }}
                    >
                        <Form.Item
                            label="Translation File"
                            name="file"
                            validateStatus={errors.file ? "error" : ""}
                            help={errors.file}
                            required
                        >
                            <Select
                                options={translationFiles}
                                value={data.file}
                                onChange={(value) => setData("file", value)}
                                size="large"
                            />
                        </Form.Item>

                        <Form.Item
                            label="Source Content"
                            name="phrase"
                            validateStatus={errors.phrase ? "error" : ""}
                            help={errors.phrase}
                            required
                        >
                            <Input.TextArea
                                rows={7}
                                placeholder="Enter source content"
                                value={data.phrase}
                                onChange={(e) => setData("phrase", e.target.value)}
                            />
                        </Form.Item>

                        <Form.Item
                            label="Note (Optional)"
                            name="note"
                            validateStatus={errors.note ? "error" : ""}
                            help={errors.note}
                        >
                            <Input.TextArea
                                rows={3}
                                placeholder="Add a note for translators"
                                value={data.note}
                                onChange={(e) => setData("note", e.target.value)}
                            />
                        </Form.Item>

                        <div className="flex gap-3 mt-6">
                            <Link href={route("ltu.source_translation")}>
                                <Button>Cancel</Button>
                            </Link>
                            <Button
                                type="primary"
                                onClick={handleSubmit}
                                loading={processing}
                                disabled={processing}
                            >
                                Save Changes
                            </Button>
                        </div>
                    </Form>
                </div>
            </div>
        </DashboardLayout>
    );
};

export default SourcePhraseEdit;
