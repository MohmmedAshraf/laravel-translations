import { useSidebar } from '@/components/ui/sidebar';
import { Bug, SquareArrowRightUp } from '@/lib/icons';

const DEFAULT_REPORT_URL =
    'https://github.com/MohmmedAshraf/laravel-translations/issues/new';

export function ReportBugLink({ url }: { url?: string }) {
    const REPORT_URL = url ?? DEFAULT_REPORT_URL;
    const { state } = useSidebar();

    if (state === 'collapsed') {
        return (
            <a
                href={REPORT_URL}
                target="_blank"
                rel="noopener noreferrer"
                className="flex size-8 items-center justify-center rounded-md border border-rose-200/60 bg-rose-50/50 transition-all hover:border-rose-300 hover:bg-rose-50 dark:border-rose-500/10 dark:bg-rose-500/5 dark:hover:border-rose-500/25 dark:hover:bg-rose-500/10"
                title="Report a Bug"
            >
                <Bug className="size-3.5 text-rose-500 dark:text-rose-400" />
            </a>
        );
    }

    return (
        <a
            href={REPORT_URL}
            target="_blank"
            rel="noopener noreferrer"
            className="group/bug flex items-center gap-2.5 rounded-lg border border-rose-200/60 bg-rose-50/50 px-3 py-2.5 transition-all hover:border-rose-300/80 hover:bg-rose-50 dark:border-rose-500/10 dark:bg-rose-500/5 dark:hover:border-rose-500/25 dark:hover:bg-rose-500/10"
        >
            <div className="flex size-6 shrink-0 items-center justify-center rounded-md border border-rose-200/80 bg-white dark:border-rose-500/20 dark:bg-rose-500/10">
                <Bug className="size-3.5 text-rose-500 dark:text-rose-400" />
            </div>
            <span className="text-xs font-medium text-rose-700/80 transition-colors group-hover/bug:text-rose-800 dark:text-rose-300/70 dark:group-hover/bug:text-rose-200">
                Report a Bug
            </span>
            <SquareArrowRightUp className="ml-auto size-3 text-rose-400/40 transition-colors group-hover/bug:text-rose-500 dark:text-rose-500/30 dark:group-hover/bug:text-rose-400" />
        </a>
    );
}
