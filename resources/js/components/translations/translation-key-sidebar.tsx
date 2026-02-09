import { router } from '@inertiajs/react';
import { useCallback, useEffect, useMemo, useState } from 'react';

import { SidebarDetailsSection } from '@/components/translations/sidebar-details-section';
import {
    Sheet,
    SheetContent,
    SheetDescription,
    SheetHeader,
    SheetTitle,
} from '@/components/ui/sheet';
import { SidebarContent } from '@/components/ui/sidebar';
import {
    Tooltip,
    TooltipContent,
    TooltipTrigger,
} from '@/components/ui/tooltip';
import { useIsMobile } from '@/hooks/use-mobile';
import {
    CloseCircle,
    DoubleAltArrowLeft,
    DoubleAltArrowRight,
} from '@/lib/icons';
import type {
    QualityCheck,
    QualityIssue,
} from '@/pages/translations/phrases/edit';
import { update } from '@/routes/ltu/source';
import type { TranslationKey } from '@/types';

interface TranslationKeySidebarProps {
    translationKey: TranslationKey;
    status?: string;
    showPrioritySelect?: boolean;
    qualityIssues?: QualityIssue[];
    qualityChecks?: QualityCheck[];
    translationIsEmpty?: boolean;
}

const SIDEBAR_WIDTH = 420;

const SECTIONS_KEY = 'ltu-sidebar-sections';
const SIDEBAR_KEY = 'ltu-right-sidebar';

const DEFAULT_OPEN: Record<string, boolean> = {
    details: true,
    quality: true,
    history: false,
};

function getSavedSections(): Record<string, boolean> {
    try {
        const saved = localStorage.getItem(SECTIONS_KEY);
        return saved ? { ...DEFAULT_OPEN, ...JSON.parse(saved) } : DEFAULT_OPEN;
    } catch {
        return DEFAULT_OPEN;
    }
}

function saveSections(state: Record<string, boolean>): void {
    try {
        localStorage.setItem(SECTIONS_KEY, JSON.stringify(state));
    } catch {
        // ignore
    }
}

export default function TranslationKeySidebar({
    translationKey,
    status: _status,
    showPrioritySelect: _showPrioritySelect = false,
}: TranslationKeySidebarProps) {
    const isMobile = useIsMobile();

    const [isOpen, setIsOpen] = useState<boolean>(() => {
        try {
            return localStorage.getItem(SIDEBAR_KEY) !== 'false';
        } catch {
            return true;
        }
    });

    const [mobileOpen, setMobileOpen] = useState(false);
    const modKey = useMemo(
        () =>
            typeof navigator !== 'undefined' &&
            /Mac|iPhone|iPad/.test(navigator.userAgent)
                ? '⌘'
                : 'Ctrl+',
        [],
    );

    const [_sections, setSections] =
        useState<Record<string, boolean>>(getSavedSections);

    const toggleSidebar = useCallback(() => {
        setIsOpen((prev) => {
            const next = !prev;
            try {
                localStorage.setItem(SIDEBAR_KEY, String(next));
            } catch {
                // ignore
            }
            return next;
        });
    }, []);

    const _toggleSection = useCallback((id: string) => {
        setSections((prev) => {
            const next = { ...prev, [id]: !prev[id] };
            saveSections(next);
            return next;
        });
    }, []);

    useEffect(() => {
        function handleKeyDown(e: KeyboardEvent): void {
            if ((e.metaKey || e.ctrlKey) && e.key === '.') {
                e.preventDefault();
                if (isMobile) {
                    setMobileOpen((prev) => !prev);
                } else {
                    toggleSidebar();
                }
            }
        }

        document.addEventListener('keydown', handleKeyDown);

        return () => document.removeEventListener('keydown', handleKeyDown);
    }, [isMobile, toggleSidebar]);

    function _handlePriorityChange(value: string): void {
        router.put(
            update.url({ translationKey: translationKey.id }),
            { priority: value },
            { preserveScroll: true },
        );
    }

    const sidebarContent = (
        <SidebarContent className="gap-0">
            <SidebarDetailsSection translationKey={translationKey} />
        </SidebarContent>
    );

    if (isMobile) {
        return (
            <>
                <Tooltip>
                    <TooltipTrigger asChild>
                        <button
                            type="button"
                            onClick={() => setMobileOpen(true)}
                            className="flex w-8 shrink-0 items-center justify-center border-l transition-colors hover:bg-sidebar-accent"
                        >
                            <DoubleAltArrowLeft className="size-4 text-sidebar-foreground/50" />
                        </button>
                    </TooltipTrigger>
                    <TooltipContent side="left">
                        Show sidebar ({modKey}.)
                    </TooltipContent>
                </Tooltip>

                <Sheet open={mobileOpen} onOpenChange={setMobileOpen}>
                    <SheetContent
                        side="right"
                        className="w-full bg-white p-0 text-sidebar-foreground dark:bg-zinc-950 [&>button.absolute]:hidden"
                    >
                        <SheetHeader className="flex flex-row items-center justify-between border-b px-4 py-3">
                            <SheetTitle className="text-sm font-medium">
                                Key Details
                            </SheetTitle>
                            <SheetDescription className="sr-only">
                                Translation key metadata and tools
                            </SheetDescription>
                            <button
                                type="button"
                                onClick={() => setMobileOpen(false)}
                                className="rounded-sm opacity-70 transition-opacity hover:opacity-100"
                            >
                                <CloseCircle className="size-4" />
                                <span className="sr-only">Close</span>
                            </button>
                        </SheetHeader>
                        {sidebarContent}
                    </SheetContent>
                </Sheet>
            </>
        );
    }

    return (
        <div className="flex h-full shrink-0">
            <Tooltip>
                <TooltipTrigger asChild>
                    <button
                        type="button"
                        onClick={toggleSidebar}
                        className="flex w-8 shrink-0 items-center justify-center border-l transition-colors hover:bg-sidebar-accent"
                    >
                        {isOpen ? (
                            <DoubleAltArrowRight className="size-4 text-sidebar-foreground/50" />
                        ) : (
                            <DoubleAltArrowLeft className="size-4 text-sidebar-foreground/50" />
                        )}
                    </button>
                </TooltipTrigger>
                <TooltipContent side="left">
                    {isOpen ? 'Hide sidebar' : 'Show sidebar'} ({modKey}.)
                </TooltipContent>
            </Tooltip>

            <aside
                className="flex h-full flex-col overflow-hidden border-l bg-white text-sidebar-foreground transition-[width] duration-200 ease-linear dark:bg-zinc-950"
                style={{ width: isOpen ? SIDEBAR_WIDTH : 0 }}
            >
                <div style={{ width: SIDEBAR_WIDTH }}>{sidebarContent}</div>
            </aside>
        </div>
    );
}
