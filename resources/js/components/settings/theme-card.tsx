import { Check } from 'lucide-react';
import { cn } from '@/lib/utils';

interface ThemeCardProps {
    label: React.ReactNode;
    isSelected: boolean;
    onClick: () => void;
    preview: React.ReactNode;
}

export function ThemeCard({
    label,
    isSelected,
    onClick,
    preview,
}: ThemeCardProps) {
    return (
        <button
            onClick={onClick}
            className={cn(
                'group relative flex flex-col rounded-xl border-2 p-3 text-left transition-all',
                isSelected
                    ? 'border-primary bg-primary/5'
                    : 'border-border hover:border-primary/50',
            )}
        >
            <div className="mb-3 aspect-[4/3] w-full overflow-hidden rounded-lg">
                {preview}
            </div>
            <div className="flex items-center gap-2">
                <div
                    className={cn(
                        'flex h-5 w-5 items-center justify-center rounded-full border-2 transition-colors',
                        isSelected
                            ? 'border-primary bg-primary'
                            : 'border-muted-foreground/30',
                    )}
                >
                    {isSelected && (
                        <Check className="h-3 w-3 text-primary-foreground" />
                    )}
                </div>
                <span className="text-sm font-medium">{label}</span>
            </div>
        </button>
    );
}

export function LightModePreview() {
    return (
        <div className="h-full w-full bg-[#f8f9fa] p-2">
            <div className="flex h-full gap-1.5">
                <div className="w-1/4 overflow-hidden rounded bg-white p-1.5">
                    <div className="mb-1 h-1.5 w-full rounded bg-gray-200" />
                    <div className="space-y-1">
                        <div className="h-1 w-full rounded bg-gray-100" />
                        <div className="h-1 w-3/4 rounded bg-gray-100" />
                    </div>
                </div>
                <div className="flex-1 rounded bg-white p-1.5">
                    <div className="mb-1.5 flex gap-1">
                        <div className="h-1 w-1 rounded-full bg-red-400" />
                        <div className="h-1 w-1 rounded-full bg-yellow-400" />
                        <div className="h-1 w-1 rounded-full bg-green-400" />
                    </div>
                    <div className="space-y-1">
                        <div className="h-1.5 w-full rounded bg-gray-100" />
                        <div className="h-1.5 w-full rounded bg-gray-100" />
                        <div className="h-1.5 w-2/3 rounded bg-gray-100" />
                    </div>
                </div>
            </div>
        </div>
    );
}

export function DarkModePreview() {
    return (
        <div className="h-full w-full bg-[#1a1b1e] p-2">
            <div className="flex h-full gap-1.5">
                <div className="w-1/4 overflow-hidden rounded bg-[#25262b] p-1.5">
                    <div className="mb-1 h-1.5 w-full rounded bg-gray-600" />
                    <div className="space-y-1">
                        <div className="h-1 w-full rounded bg-gray-700" />
                        <div className="h-1 w-3/4 rounded bg-gray-700" />
                    </div>
                </div>
                <div className="flex-1 rounded bg-[#25262b] p-1.5">
                    <div className="mb-1.5 flex gap-1">
                        <div className="h-1 w-1 rounded-full bg-red-400" />
                        <div className="h-1 w-1 rounded-full bg-yellow-400" />
                        <div className="h-1 w-1 rounded-full bg-green-400" />
                    </div>
                    <div className="space-y-1">
                        <div className="h-1.5 w-full rounded bg-gray-700" />
                        <div className="h-1.5 w-full rounded bg-gray-700" />
                        <div className="h-1.5 w-2/3 rounded bg-gray-700" />
                    </div>
                </div>
            </div>
        </div>
    );
}

export function SystemPreview() {
    return (
        <div className="h-full w-full overflow-hidden rounded">
            <div className="flex h-full">
                <div className="w-1/2 bg-[#f8f9fa] p-1.5">
                    <div className="h-full rounded bg-white p-1">
                        <div className="space-y-0.5">
                            <div className="h-1 w-full rounded bg-gray-100" />
                            <div className="h-1 w-full rounded bg-gray-100" />
                        </div>
                    </div>
                </div>
                <div className="w-1/2 bg-[#1a1b1e] p-1.5">
                    <div className="h-full rounded bg-[#25262b] p-1">
                        <div className="space-y-0.5">
                            <div className="h-1 w-full rounded bg-gray-700" />
                            <div className="h-1 w-full rounded bg-gray-700" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
