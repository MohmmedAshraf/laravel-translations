import { Link, InertiaLinkProps } from '@inertiajs/react';
import classNames from 'classnames';

interface NavLinkProps extends InertiaLinkProps {
    active?: boolean;
    className?: string;
}

export default function NavLink({ active = false, className = '', children, ...props }: NavLinkProps) {
    const linkClassName = classNames(
        'rounded-md px-3 py-2 text-sm font-medium transition duration-150 ease-in-out',
        {
            'bg-blue-500 text-white': active,
            'text-white hover:bg-blue-700 hover:text-white': !active,
        },
        className
    );

    return (
        <Link {...props} className={linkClassName}>
            {children}
        </Link>
    );
}