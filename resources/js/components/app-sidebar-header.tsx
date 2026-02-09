import { router } from '@inertiajs/react';
import { useCallback, useState, type MouseEvent } from 'react';
import { Breadcrumbs } from '@/components/breadcrumbs';
import { Button } from '@/components/ui/button';
import { SidebarTrigger } from '@/components/ui/sidebar';
import { Spinner } from '@/components/ui/spinner';
import { useAppearance } from '@/hooks/use-appearance';
import { Moon, Sun2, Upload } from '@/lib/icons';
import { exportMethod } from '@/routes/ltu';
import type { BreadcrumbItem as BreadcrumbItemType } from '@/types';

export function AppSidebarHeader({
    breadcrumbs = [],
}: {
    breadcrumbs?: BreadcrumbItemType[];
}) {
    const [exporting, setExporting] = useState(false);
    const { resolvedAppearance, updateAppearance } = useAppearance();

    const handleExport = useCallback(() => {
        setExporting(true);
        router.post(
            exportMethod.url(),
            {},
            {
                onFinish: () => setExporting(false),
            },
        );
    }, []);

    const toggleTheme = useCallback(
        (e: MouseEvent<HTMLButtonElement>) => {
            const newMode = resolvedAppearance === 'dark' ? 'light' : 'dark';

            if (!document.startViewTransition) {
                updateAppearance(newMode);
                return;
            }

            const x = e.clientX;
            const y = e.clientY;
            const endRadius = Math.hypot(
                Math.max(x, window.innerWidth - x),
                Math.max(y, window.innerHeight - y),
            );

            const transition = document.startViewTransition(() => {
                updateAppearance(newMode);
            });

            transition.ready.then(() => {
                document.documentElement.animate(
                    {
                        clipPath: [
                            `circle(0px at ${x}px ${y}px)`,
                            `circle(${endRadius}px at ${x}px ${y}px)`,
                        ],
                    },
                    {
                        duration: 500,
                        easing: 'ease-in-out',
                        pseudoElement: '::view-transition-new(root)',
                    },
                );
            });
        },
        [resolvedAppearance, updateAppearance],
    );

    return (
        <header className="flex h-16 shrink-0 items-center gap-2 border-b border-sidebar-border/50 px-6 transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12 md:px-4">
            <div className="flex items-center gap-2">
                <SidebarTrigger className="-ml-1" />
                <Breadcrumbs breadcrumbs={breadcrumbs} />
            </div>
            <div className="ml-auto flex items-center gap-2">
                <Button onClick={handleExport} disabled={exporting}>
                    {exporting ? (
                        <Spinner className="size-4" />
                    ) : (
                        <Upload className="size-4" />
                    )}
                    Export
                </Button>
                <Button
                    variant="ghost"
                    size="icon"
                    onClick={toggleTheme}
                    className="relative h-9 w-9"
                >
                    <Sun2 className="size-5 scale-100 rotate-0 transition-transform duration-300 dark:scale-0 dark:-rotate-90" />
                    <Moon className="absolute size-5 scale-0 rotate-90 transition-transform duration-300 dark:scale-100 dark:rotate-0" />
                    <span className="sr-only">Toggle theme</span>
                </Button>
            </div>
        </header>
    );
}
