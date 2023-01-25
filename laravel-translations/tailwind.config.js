const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    presets: [
        require('./vendor/wireui/wireui/tailwind.config.js')
    ],
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './vendor/wireui/wireui/resources/**/*.blade.php',
        './vendor/wireui/wireui/ts/**/*.ts',
        './vendor/wireui/wireui/src/View/**/*.php',
        './vendor/wire-elements/modal/resources/views/*.blade.php'
    ],
    safelist: [
        {
            pattern: /max-w-(sm|md|lg|xl|2xl|3xl|4xl|5xl|6xl|7xl)/,
            variants: ['sm', 'md', 'lg', 'xl', '2xl'],
        },
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter var', ...defaultTheme.fontFamily.sans],
            },
            minHeight: {
                '20': '5rem',
                '30': '7.5rem',
                '36': '9rem',
                '38': '9.5rem',
                '40': '10rem',
                '42': '10.5rem',
            },
            minWidth: {
                '40': '10rem',
                '2xl': '42rem',
            },
            maxWidth: {
                '24': '6rem',
                '40': '10rem',
            }
        },
    },

    plugins: [
        require('@tailwindcss/forms')
    ],
};
