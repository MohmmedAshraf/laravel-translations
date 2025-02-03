import React from "react"
import { Button, Tooltip } from "antd"
import { Link } from "@inertiajs/react"
import { Phrase, Translation } from "@/types"
import CountryFlag from "@/Components/Language/Flag"
import { ClipboardIcon, DocumentIcon, PencilIcon, TrashIcon} from "@heroicons/react/24/outline"
import RenderPhraseWithParameters from "@/Components/Phrase/RenderPhraseWithParameters"
import IconSpeak from "@/Components/Icons/IconSpeak"
// @ts-ignore
import { useSpeechSynthesis } from 'react-speech-kit';
import { StopIcon } from "@heroicons/react/24/solid"

export interface Props {
    phrase: Phrase;
    translation: Translation;
}

const SourcePhrase: React.FC<Props> = ({ phrase, translation }) => {
    const { speak, cancel, speaking, supported, voices } = useSpeechSynthesis();
    return (
        <div className="relative flex flex-col w-full overflow-hidden rounded-md bg-white shadow ring-2 ring-blue-100">
            <div className="flex items-center justify-between border-b">
                <div className="flex gap-2 px-4 py-2.5">
                    <div className="h-5 shrink-0">
                        <CountryFlag countryCode={translation.language.code} />
                    </div>

                    <div className="flex items-center space-x-2">
                        <div className="text-sm font-semibold text-gray-800">
                            {translation.language?.name}
                        </div>

                        <div className="rounded-md border px-1.5 py-0.5 text-xs text-gray-500">
                            {translation.language?.code}
                        </div>
                    </div>
                </div>

                <div className="flex items-center divide-x border-l">
                    <button type="button" className="group h-full p-3 hover:bg-blue-50">
                        <ClipboardIcon className="size-5 text-gray-400 group-hover:text-blue-600" />
                    </button>

                    <Link href="#" type="button" className="group h-full p-3 hover:bg-blue-50">
                        <PencilIcon className="size-5 text-gray-400 group-hover:text-blue-600" />
                    </Link>

                    <button type="button" className="group h-full p-3 hover:bg-red-50">
                        <TrashIcon className="size-5 text-gray-400 group-hover:text-red-600" />
                    </button>
                </div>
            </div>

            <div dir="auto" className="flex w-full px-4 py-2.5">
                <div className="flex size-full flex-wrap gap-x-1 gap-y-0.5">
                    <RenderPhraseWithParameters
                        value={phrase?.value}
                        parameters={phrase?.parameters}
                    />
                </div>
            </div>

            <div className="flex h-12 w-full mt-auto items-center justify-center gap-4 border-t border-blue-200 px-4 py-1.5">
                <div className="flex w-full select-none items-center gap-1 text-gray-400">
                    <DocumentIcon className="size-4" />

                    <div className="text-xs">
                        {phrase?.file?.name_with_extension}
                    </div>
                </div>

                {speaking ? (
                    <Tooltip title="Stop">
                        <Button
                            type="text"
                            onClick={cancel}
                            className="px-1.5 text-blue-500"
                        >
                            <StopIcon className="size-5" />
                        </Button>
                    </Tooltip>
                ) : (
                    <Tooltip title="Listen">
                        <Button
                            type="text"
                            onClick={() => speak({
                                text: phrase?.value,
                                lang: "ar-001"
                            })}
                            className="px-1.5 text-gray-500"
                        >
                            <IconSpeak className="size-5" />
                        </Button>
                    </Tooltip>
                )}
            </div>
        </div>
    )
}

export default SourcePhrase;