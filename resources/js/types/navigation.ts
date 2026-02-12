import type { InertiaLinkProps } from '@inertiajs/react';
import type { IconComponent } from '@/lib/icons';

export type BreadcrumbItem = {
    title: string;
    href: string;
};

export type NavItem = {
    title: string;
    href: NonNullable<InertiaLinkProps['href']>;
    icon?: IconComponent | null;
    isActive?: boolean;
    badge?: number;
};
