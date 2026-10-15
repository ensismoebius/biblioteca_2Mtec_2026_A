import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            colors: {
                library: {
                    background: '#0a0a0a',
                    surface: '#262626',
                    'surface-hover': '#3f3f46',
                    border: '#52525b',
                    text: '#f5f5f4',
                    muted: '#d4d4d8',
                    accent: '#a5b4fc',
                    secondary: '#191970',
                },
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
