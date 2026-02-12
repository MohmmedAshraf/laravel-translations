import type { SVGAttributes } from 'react';

export default function AppLogoIcon(props: SVGAttributes<SVGElement>) {
    return (
        <svg {...props} viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path
                fill="currentColor"
                d="M20 2H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h3.59l3.7 3.71a1 1 0 0 0 1.42 0l3.7-3.71H20a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2Zm-4 7h-1.07a8.33 8.33 0 0 1-1.43 3.74 3.43 3.43 0 0 0 .74.29A1 1 0 0 1 14 15h-.24a6.51 6.51 0 0 1-1.76-.81 5.6 5.6 0 0 1-1.77.78L10 15a1 1 0 0 1-.24-2 3.81 3.81 0 0 0 .51-.17 6.75 6.75 0 0 1-1.13-1.33 1 1 0 0 1 1.72-1 5.07 5.07 0 0 0 1 1.11A6.09 6.09 0 0 0 12.9 9H8a1 1 0 0 1 0-2h3a1 1 0 0 1 2 0h3a1 1 0 0 1 0 2Z"
            />
        </svg>
    );
}
