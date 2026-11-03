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
                'custom-blue': '#0b1f33',
                'custom-blue-100': '#0b1f33',
                'custom-red': '#0d9488',
                'custom-blue-500': '#578FCA',
                'custom-blue-600': '#3674B5',
                'custom-blue-700': '#122748', // versi lebih gelap untuk hover/focus
                'custom-blue-800': '#0e1e36', // versi lebih gelap untuk active                                         
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [
        forms,
        function({ addBase }) {
            addBase({
                'input[type="password"]::-ms-reveal, input[type="password"]::-ms-clear': {
                    display: 'none !important',
                },
                'input[type="password"]::-webkit-credentials-auto-fill-button, input[type="password"]::-webkit-contacts-auto-fill-button': {
                    visibility: 'hidden',
                    pointerEvents: 'none',
                    position: 'absolute',
                    right: '0',
                }
            });
        }
    ],
}