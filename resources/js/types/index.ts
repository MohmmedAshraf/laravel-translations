export type * from './auth';
export type * from './navigation';
export type * from './translations';
export type * from './ui';

import type { Auth } from './auth';

export type SharedData = {
    name: string;
    auth: Auth;
    sidebarOpen: boolean;
    environment: string;
    isContributorMode: boolean;
    [key: string]: unknown;
};
