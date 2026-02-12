import { Badge } from '@/components/ui/badge';
import { Flag } from '@/components/ui/flag';
import type { Language } from '@/types';

interface LanguageBarProps {
    language: Language;
    children?: React.ReactNode;
}

export function LanguageBar({ language, children }: LanguageBarProps) {
    return (
        <div className="flex h-11 shrink-0 items-center justify-between border-b px-4">
            <div className="flex items-center gap-2">
                <Flag code={language.code} className="size-4 shrink-0" />
                <span className="text-sm font-medium">{language.name}</span>
                <Badge variant="outline" className="font-mono text-xs">
                    {language.code}
                </Badge>
            </div>
            {children}
        </div>
    );
}
