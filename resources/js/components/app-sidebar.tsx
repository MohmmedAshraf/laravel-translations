import { Link } from '@inertiajs/react';
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
    Bug,
    Languages,
    Library,
    SquareArrowRightUp,
    Stars,
} from '@/lib/icons';
import { index as groupsIndex } from '@/routes/ltu/groups';
import { index as languagesIndex } from '@/routes/ltu/languages';
import type { NavItem } from '@/types';
import AppLogo from './app-logo';

export function AppSidebar() {
    const { state } = useSidebar();
    const isCollapsed = state === 'collapsed';

    const mainNavItems: NavItem[] = [
        {
            title: 'Translations',
            href: languagesIndex().url,
            icon: Languages,
        },
        {
            title: 'Groups',
            href: groupsIndex().url,
            icon: Library,
        },
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
                        <a
                            href="https://github.com/MohmmedAshraf/laravel-translations/issues/new"
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

                        <a
                            href="https://github.com/MohmmedAshraf/laravel-translations"
                            target="_blank"
                            rel="noopener noreferrer"
                            className="group/pro relative block overflow-hidden rounded-xl bg-gradient-to-r from-indigo-300 via-violet-300 to-purple-300 p-[1px] shadow-sm transition-all hover:shadow-lg hover:shadow-indigo-300/30 dark:from-indigo-400 dark:via-violet-400 dark:to-purple-400 dark:hover:shadow-indigo-400/15"
                        >
                            <div
                                className="animate-border-beam pointer-events-none absolute top-1/2 left-1/2 size-[200%]"
                                style={{
                                    background:
                                        'conic-gradient(from 0deg, transparent 0%, transparent 72%, rgba(167,139,250,0.5) 78%, white 82%, rgba(167,139,250,0.5) 86%, transparent 92%, transparent 100%)',
                                }}
                            />

                            <div className="relative overflow-hidden rounded-[11px] bg-gradient-to-br from-white via-white to-indigo-50/80 px-3 py-3 dark:from-[#0c0c14] dark:via-[#0f0f1a] dark:to-[#111118]">
                                <div className="pointer-events-none absolute inset-0 overflow-hidden">
                                    <div className="animate-shimmer absolute inset-y-0 -left-full w-1/2 bg-gradient-to-r from-transparent via-indigo-400/[0.04] to-transparent dark:via-white/[0.06]" />
                                </div>

                                <div className="relative flex items-center justify-between">
                                    <div className="flex items-center gap-2.5">
                                        <div className="flex size-7 shrink-0 items-center justify-center rounded-lg bg-gradient-to-br from-indigo-100 to-indigo-100 ring-1 ring-indigo-200/60 dark:from-indigo-500/20 dark:to-violet-500/20 dark:ring-white/10">
                                            <Stars className="size-4 text-violet-500 dark:text-amber-400" />
                                        </div>
                                        <div>
                                            <p className="text-xs font-semibold tracking-wide text-slate-800 dark:text-white/90">
                                                Upgrade to Pro
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
                        <a
                            href="https://github.com/MohmmedAshraf/laravel-translations/issues/new"
                            target="_blank"
                            rel="noopener noreferrer"
                            className="flex size-8 items-center justify-center rounded-md border border-rose-200/60 bg-rose-50/50 transition-all hover:border-rose-300 hover:bg-rose-50 dark:border-rose-500/10 dark:bg-rose-500/5 dark:hover:border-rose-500/25 dark:hover:bg-rose-500/10"
                            title="Report a Bug"
                        >
                            <Bug className="size-3.5 text-rose-500 dark:text-rose-400" />
                        </a>

                        <a
                            href="https://github.com/MohmmedAshraf/laravel-translations"
                            target="_blank"
                            rel="noopener noreferrer"
                            className="group/pro relative block overflow-hidden rounded-lg bg-gradient-to-r from-indigo-300 via-violet-300 to-purple-300 p-[1px] transition-shadow hover:shadow-lg hover:shadow-indigo-300/30 dark:from-indigo-400 dark:via-violet-400 dark:to-purple-400 dark:hover:shadow-indigo-400/15"
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
