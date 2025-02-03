import { Language } from "@/types";
import React, { useState } from "react";
import { useForm } from "@inertiajs/react";
import IconSearch from "@/Components/Icons/IconSearch";
import LanguageTag from "@/Components/Language/LanguageTag";
import { Checkbox, Form, message, Modal, Select } from "antd";

interface AddTranslationsProps {
    languages: Language[];
    isModalOpen: boolean;
    setIsModalOpen: (value: boolean) => void;
}

const AddTranslations: React.FC<AddTranslationsProps> = ({ languages, isModalOpen, setIsModalOpen }) => {
    const [componentKey, setComponentKey] = useState(0);

    const { data, setData, errors, post, processing } = useForm({
        language_ids: [],
    });

    const handleCancel = () => {
        setIsModalOpen(false);
        resetForm();
    };

    const resetForm = () => {
        setData('language_ids', []);
        setComponentKey(prevKey => prevKey + 1);
    };

    const add = () => {
        post(route('ltu.translation.store'), {
            preserveScroll: true,
            onSuccess: () => {
                message.success('Languages added successfully').then();
                resetForm();
                setIsModalOpen(false);
            },
            onFinish: () => {
                setComponentKey(prevKey => prevKey + 1);
            }
        });
    };

    return (
        <Modal
            onOk={add}
            open={isModalOpen}
            onCancel={handleCancel}
            title="Add Languages"
            okText="Add Languages"
            okButtonProps={{
                size: 'large',
                loading: processing,
                disabled: processing || data.language_ids.length === 0,
            }}
            cancelButtonProps={{
                size: 'large',
                disabled: processing,
            }}
        >
            <Form
                name="basic"
                layout='vertical'
                key={componentKey}
                initialValues={data}
                onFieldsChange={(changedFields) => {
                    changedFields.forEach(item => {
                        setData(item.name[0], item.value);
                    });
                }}
                autoComplete="off"
            >
                <Form.Item
                    className="mb-4"
                    name="language_ids"
                    help={errors.language_ids}
                    validateStatus={errors.language_ids && 'error'}
                >
                    <Select
                        showSearch
                        size="large"
                        mode="multiple"
                        suffixIcon={<IconSearch className="size-5" />}
                        placeholder="Select a language"
                        filterOption={(input, option) =>
                            (option?.search ?? '').toLowerCase().includes(input.toLowerCase())
                        }
                        options={languages.map((language) => ({
                            value: language.id,
                            label: <LanguageTag className="py-0.5" language={language} />,
                            search: language.name.toLowerCase(),
                        }))}
                    />
                </Form.Item>

                <div className="flex items-center justify-between">
                    <Form.Item
                        className="mb-0"
                        name="pretranslate"
                        valuePropName="checked"
                    >
                        <Checkbox>Pre-translate selected languages with Machine Translations</Checkbox>
                    </Form.Item>
                </div>
            </Form>
        </Modal>
    );
};

export default AddTranslations;