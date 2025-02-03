import React from 'react';
import { Popover } from "antd";

interface RenderPhraseWithParametersProps {
    value: string;
    parameters?: string[] | null;
}

const RenderPhraseWithParameters: React.FC<RenderPhraseWithParametersProps> = ({ value, parameters = [] }) => {
    if (!value || !parameters || parameters.length === 0) {
        return <>{value}</>;
    }

    const regex = /(:\w+|\s+)/g;
    const parts = value.split(regex).filter(part => part !== "");

    return (
        <span className="w-full line-clamp-1">
            {parts.map((part, index) => {
                if (part.startsWith(':')) {
                    const cleanedPart = part.replace(/^:/, '');
                    if (parameters.includes(cleanedPart)) {
                        return (
                            <span key={index} className="cursor-pointer text-blue-600 font-medium">
                                <Popover
                                    placement="topLeft"
                                    title={(
                                        <span className="text-blue-600 font-medium">
                                            {part}
                                        </span>
                                    )}
                                    content={(
                                        <span className="text-gray-500">
                                            This is a content placeholder, <br /> it will be replaced dynamically with varying values in the application.
                                        </span>
                                    )}
                                >
                                    {part}
                                </Popover>
                            </span>
                        );
                    }
                }

                return part;
            })}
        </span>
    );
};

export default RenderPhraseWithParameters;