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
            fontFamily: {
                sans: ['Public Sans', ...defaultTheme.fontFamily.sans],
                'manrope': ['Manrope', 'sans-serif'],
                'ibm': ['IBM Plex Mono', 'monospace'],
            },
            colors: {
                'gray': {
                    400: '#919191',
                    500: '#C4C4C4',
                    600: '#5E5E5E',
                    700: '#303136',
                    800: '#242529',
                    900: '#1E1F22',
                },
                'stroke': {
                    'primary': '#2B2C31',
                    'secondary': '#FFD700',
                },
                'form': {
                    'input': '#1D1D21',
                    'stroke': '#29292E',
                    'placeholder': '#2B2B31',
                },
                'button': {
                    'primary': '#463DDC',
                    'primary-hover': '#5A52E0',
                    'primary-focus': '#2C23C2',
                    'secondary': '#37383E',
                    'secondary-hover': '#43454C',
                    'secondary-focus': '#43454C',
                },
                'container': {
                    'primary': '#2A2A2F',
                    'light': '#323339',
                }
            },
        },
    },

    plugins: [
        require('tailwind-scrollbar')({
            nocompatible: true
        }),
        require('@tailwindcss/forms'),
    ],
};
