import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'github-dark': '#0d1117',
                'github-darker': '#010409',
                'github-light': '#e6edf3',
                'github-muted': '#8b949e',
                'github-border': '#30363d',
                'github-blue': '#58a6ff',
                'github-purple': '#6e40aa',
            },
            backgroundColor: {
                'gh-dark': '#0d1117',
                'gh-darker': '#010409',
                'gh-surface': '#161b22',
            },
            borderColor: {
                'gh-border': '#30363d',
            },
            textColor: {
                'gh-light': '#e6edf3',
                'gh-muted': '#8b949e',
            },
        },
    },

    plugins: [forms],
};
