import React from 'react';
import { Link } from '@inertiajs/react';
import CountryFlag from "@/Components/Language/Flag";
import { ChevronRightIcon } from "@heroicons/react/24/outline";
import IconKey from "@/Components/Icons/IconKey";
import { Phrase, Translation } from "@/types"

export interface Props {
    phrase: Phrase;
    translation: Translation;
}

const PhraseHeader: React.FC<Props> = ({ phrase, translation }) => {
    return(
        <>
            <div className="flex w-full items-center">
                <div className="flex w-full items-center gap-3 py-4">
                    <Link href="#" className="flex items-center gap-2 rounded-md border border-transparent bg-gray-50 px-2 py-1 hover:border-blue-400 hover:bg-blue-100">
                        <div className="h-5 shrink-0">
                            <CountryFlag countryCode={translation.language.code} />
                        </div>
                        <div className="flex items-center space-x-2">
                            <div className="text-sm font-semibold text-gray-600">{translation.language.name}</div>
                        </div>
                    </Link>
                    <div><ChevronRightIcon className="size-6 text-gray-400" /></div>
                    <div className="flex items-center gap-2 rounded-md border border-blue-100 bg-blue-50 px-2 py-1 text-blue-500">
                        <IconKey className="size-4" />
                        <span className="text-sm">{phrase.key}</span>
                    </div>
                </div>
            </div>

            <Link href="#" className="flex size-10 items-center justify-center rounded-full bg-gray-100 p-1 hover:bg-gray-200">
                <ChevronRightIcon className="size-6 text-gray-400" />
            </Link>
        </>
    );
}

export default PhraseHeader;