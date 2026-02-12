import { Link, router } from '@inertiajs/react';
import {
    DropdownMenuLabel,
    DropdownMenuItem,
    DropdownMenuSeparator,
} from '@/components/ui/dropdown-menu';
import { UserInfo } from '@/components/user-info';
import { useSettingsDialog } from '@/contexts/settings-dialog-context';
import { useMobileNavigation } from '@/hooks/use-mobile-navigation';
import { Logout2, Settings } from '@/lib/icons';
import { logout } from '@/routes/ltu';
import type { User } from '@/types';

type Props = {
    user: User;
};

export function UserMenuContent({ user }: Props) {
    const cleanup = useMobileNavigation();
    const { open: openSettings } = useSettingsDialog();

    const handleOpenSettings = () => {
        cleanup();
        openSettings();
    };

    const handleLogout = () => {
        cleanup();
        router.flushAll();
    };

    return (
        <>
            <DropdownMenuLabel className="p-0 font-normal">
                <div className="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                    <UserInfo user={user} showEmail={true} />
                </div>
            </DropdownMenuLabel>
            <DropdownMenuSeparator />
            <DropdownMenuItem onClick={handleOpenSettings}>
                <Settings className="mr-2" />
                Settings
            </DropdownMenuItem>
            <DropdownMenuItem asChild>
                <Link
                    className="block w-full cursor-pointer"
                    href={logout().url}
                    method="post"
                    as="button"
                    onClick={handleLogout}
                    data-test="logout-button"
                >
                    <Logout2 className="mr-2" />
                    Log out
                </Link>
            </DropdownMenuItem>
        </>
    );
}
