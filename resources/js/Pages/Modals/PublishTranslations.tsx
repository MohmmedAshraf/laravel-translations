import React, { useState } from "react";
import { message, Modal, Button, Alert, Spin } from "antd";
import { ArrowDownTrayIcon } from "@heroicons/react/24/outline";

interface PublishTranslationsProps {
    isOpen: boolean;
    onClose: () => void;
    canPublish: boolean;
    isProductionEnv: boolean;
}

const PublishTranslations: React.FC<PublishTranslationsProps> = ({
    isOpen,
    onClose,
    canPublish,
    isProductionEnv,
}) => {
    const [loading, setLoading] = useState(false);
    const [downloadLoading, setDownloadLoading] = useState(false);

    const handlePublish = async () => {
        try {
            setLoading(true);
            const response = await window.axios.post(route('ltu.translation.publish'));
            if (response.status === 200) {
                message.success('Translations published successfully');
                onClose();
            }
        } catch (error) {
            message.error('Failed to publish translations');
        } finally {
            setLoading(false);
        }
    };

    const handleDownload = async () => {
        try {
            setDownloadLoading(true);
            const response = await window.axios.get(route('ltu.translation.download'), {
                responseType: 'blob',
            });
            
            // Create a blob URL and trigger download
            const url = window.URL.createObjectURL(new Blob([response.data]));
            const link = document.createElement('a');
            link.href = url;
            link.setAttribute('download', 'lang.zip');
            document.body.appendChild(link);
            link.click();
            link.parentNode?.removeChild(link);
            window.URL.revokeObjectURL(url);
            
            onClose();
        } catch (error) {
            message.error('Something went wrong, please try again.');
        } finally {
            setDownloadLoading(false);
        }
    };

    const content = canPublish ? (
        <>
            <div className="mb-4">
                <h3 className="text-lg font-semibold text-gray-900 mb-2">
                    Publish Translations
                </h3>
                <p className="text-gray-600">
                    Your translations are ready to be published. Click the button below to export
                    your translations and make them available in your application.
                </p>
            </div>

            {isProductionEnv && (
                <Alert
                    message="Production Environment"
                    description="You are in a production environment. Please download your translations first before publishing."
                    type="warning"
                    showIcon
                    className="mb-4"
                />
            )}
        </>
    ) : (
        <div className="flex flex-col items-center justify-center py-8">
            <div className="text-center">
                <h3 className="text-lg font-semibold text-gray-900 mb-2">
                    No Languages to Publish
                </h3>
                <p className="text-gray-600">
                    You need to add at least one language before you can publish translations.
                </p>
            </div>
        </div>
    );

    return (
        <Modal
            title="Publish Translations"
            open={isOpen}
            onCancel={onClose}
            footer={[
                <Button key="close" onClick={onClose}>
                    Close
                </Button>,
                canPublish && (
                    <Button
                        key="download"
                        type="default"
                        icon={<ArrowDownTrayIcon className="size-5" />}
                        onClick={handleDownload}
                        loading={downloadLoading}
                        disabled={loading || downloadLoading}
                    >
                        Download
                    </Button>
                ),
                canPublish && !isProductionEnv && (
                    <Button
                        key="publish"
                        type="primary"
                        onClick={handlePublish}
                        loading={loading}
                        disabled={loading || downloadLoading}
                    >
                        Publish
                    </Button>
                ),
            ]}
        >
            <Spin spinning={loading || downloadLoading}>
                {content}
            </Spin>
        </Modal>
    );
};

export default PublishTranslations;
