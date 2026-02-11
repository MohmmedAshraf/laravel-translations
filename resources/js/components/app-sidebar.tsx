import { Link, usePage } from '@inertiajs/react';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
    useSidebar,
} from '@/components/ui/sidebar';
import {
    Contributor,
    DocumentText,
    Languages,
    Library,
    SquareArrowRightUp,
    Stars,
} from '@/lib/icons';
import { index as contributorsIndex } from '@/routes/ltu/contributors';
import { index as groupsIndex } from '@/routes/ltu/groups';
import { index as languagesIndex } from '@/routes/ltu/languages';
import { index as sourceIndex } from '@/routes/ltu/source';
import type { SharedData } from '@/types';
import type { NavItem } from '@/types';
import AppLogo from './app-logo';
import { ReportBugLink } from './report-bug-link';

export function AppSidebar() {
    const { state } = useSidebar();
    const isCollapsed = state === 'collapsed';
    const { auth } = usePage<SharedData>().props;
    const canManageContributors = ['owner', 'admin'].includes(auth.role ?? '');

    const mainNavItems: NavItem[] = [
        {
            title: 'Translations',
            href: languagesIndex().url,
            icon: Languages,
        },
        {
            title: 'Source Language',
            href: sourceIndex().url,
            icon: DocumentText,
        },
        {
            title: 'Groups',
            href: groupsIndex().url,
            icon: Library,
        },
        ...(canManageContributors
            ? [
                  {
                      title: 'Contributors',
                      href: contributorsIndex().url,
                      icon: Contributor,
                  },
              ]
            : []),
    ];

    return (
        <Sidebar collapsible="icon" variant="inset">
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href={languagesIndex().url} prefetch>
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            <SidebarContent>
                <NavMain items={mainNavItems} />
            </SidebarContent>

            <SidebarFooter>
                {!isCollapsed && (
                    <div className="mx-2 space-y-2">
                        <ReportBugLink />

                        <a
                            href="https://outhebox.dev/products/translations-pro?utm_source=app_sidebar&utm_medium=link&utm_campaign=upgrade_to_pro"
                            target="_blank"
                            rel="noopener noreferrer"
                            className="group/pro relative block overflow-hidden rounded-xl bg-linear-to-r from-indigo-300 via-violet-300 to-purple-300 p-px shadow-sm transition-all hover:shadow-lg hover:shadow-indigo-300/30 dark:from-indigo-400 dark:via-violet-400 dark:to-purple-400 dark:hover:shadow-indigo-400/15"
                        >
                            <div
                                className="animate-border-beam pointer-events-none absolute top-1/2 left-1/2 size-[200%]"
                                style={{
                                    background:
                                        'conic-gradient(from 0deg, transparent 0%, transparent 72%, rgba(167,139,250,0.5) 78%, white 82%, rgba(167,139,250,0.5) 86%, transparent 92%, transparent 100%)',
                                }}
                            />

                            <div className="relative overflow-hidden rounded-[11px] bg-linear-to-br from-white via-white to-indigo-50/80 px-3 py-3 dark:from-[#0c0c14] dark:via-[#0f0f1a] dark:to-[#111118]">
                                <div className="pointer-events-none absolute inset-0 overflow-hidden">
                                    <div className="animate-shimmer absolute inset-y-0 -left-full w-1/2 bg-linear-to-r from-transparent via-indigo-400/4 to-transparent dark:via-white/6" />
                                </div>

                                <div className="relative flex items-center justify-between">
                                    <div className="flex items-center gap-2.5">
                                        <div className="flex size-7 shrink-0 items-center justify-center rounded-lg bg-linear-to-br from-indigo-100 to-indigo-100 ring-1 ring-indigo-200/60 dark:from-indigo-500/20 dark:to-violet-500/20 dark:ring-white/10">
                                            <Stars className="size-4 text-violet-500 dark:text-amber-400" />
                                        </div>
                                        <div>
                                            <p className="text-xs font-semibold tracking-wide text-slate-800 dark:text-white/90">
                                                Pro Available
                                            </p>
                                            <p className="mt-0.5 text-[10px] leading-tight text-slate-500 dark:text-slate-400">
                                                AI, glossary, reviews & more
                                            </p>
                                        </div>
                                    </div>
                                    <SquareArrowRightUp className="size-3.5 text-indigo-300 transition-colors group-hover/pro:text-indigo-500 dark:text-slate-600 dark:group-hover/pro:text-indigo-400" />
                                </div>
                            </div>
                        </a>
                    </div>
                )}
                {isCollapsed && (
                    <div className="flex flex-col items-center gap-2">
                        <ReportBugLink />

                        <a
                            href="https://outhebox.dev/products/translations-pro?utm_source=app_sidebar&utm_medium=link&utm_campaign=upgrade_to_pro"
                            target="_blank"
                            rel="noopener noreferrer"
                            className="group/pro relative block overflow-hidden rounded-lg bg-linear-to-r from-indigo-300 via-violet-300 to-purple-300 p-px transition-shadow hover:shadow-lg hover:shadow-indigo-300/30 dark:from-indigo-400 dark:via-violet-400 dark:to-purple-400 dark:hover:shadow-indigo-400/15"
                            title="Upgrade to Pro"
                        >
                            <div
                                className="animate-border-beam pointer-events-none absolute top-1/2 left-1/2 size-[200%]"
                                style={{
                                    background:
                                        'conic-gradient(from 0deg, transparent 0%, transparent 72%, rgba(167,139,250,0.5) 78%, white 82%, rgba(167,139,250,0.5) 86%, transparent 92%, transparent 100%)',
                                }}
                            />
                            <div className="relative flex size-8 items-center justify-center overflow-hidden rounded-[7px] bg-white dark:bg-[#0c0c14]">
                                <Stars className="relative size-4 text-violet-500 dark:text-amber-400" />
                            </div>
                        </a>
                    </div>
                )}

                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
