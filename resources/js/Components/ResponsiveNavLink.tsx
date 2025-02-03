import { Link, InertiaLinkProps } from '@inertiajs/react';
import classNames from 'classnames';

interface ResponsiveNavLinkProps extends InertiaLinkProps {
    active?: boolean;
    className?: string;
}

export default function ResponsiveNavLink({ active = false, className = '', children, ...props }: ResponsiveNavLinkProps) {
    const linkClassName = classNames(
        'w-full flex items-start ps-3 pe-4 py-2 border-l-4 text-base font-medium focus:outline-none transition duration-150 ease-in-out',
        {
            'border-blue-400 text-blue-700 bg-blue-50 focus:text-blue-800 focus:bg-blue-100 focus:border-blue-700': active,
            'border-transparent text-white hover:text-blue-800 hover:bg-blue-50 hover:border-blue-300 focus:text-blue-800 focus:bg-blue-50 focus:border-blue-300': !active,
        },
        className
    );

    return (
        <Link {...props} className={linkClassName}>
            {children}
        </Link>
    );
}