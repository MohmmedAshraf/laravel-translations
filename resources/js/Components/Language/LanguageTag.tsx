import { Language } from "@/types";
import React from "react";
import Flag from "@/Components/Language/Flag";
import classNames from "classnames";

interface LanguageTagProps {
    language: Language;
    className?: string;
}

const LanguageTag: React.FC<LanguageTagProps> = ({ language, className }) => {
    return (
        <div className={classNames("flex items-center w-full font-medium truncate", className)}>
            <div className="text-xl mr-2 leading-none">
                <Flag countryCode={language.code} />
            </div>
            <div className="mr-2 max-w-full text-base text-gray-700 truncate">
                {language.name}
            </div>
            <div className="inline-block">
                <div className="px-1 flex items-center h-5 rounded leading-none border text-xs font-normal border-gray-200 text-gray-500">
                    {language.code}
                </div>
            </div>
        </div>
    );
}

export default LanguageTag;