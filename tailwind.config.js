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
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'taspen-blue': '#1557A6',
                'taspen-dark-blue': '#0B3B73',
                'taspen-light-blue': '#EAF3FF',
                'taspen-yellow': '#F4C430',
                'taspen-orange': '#F28C28',
                'taspen-bg': '#F6F8FC',
                'taspen-text': '#1F2937',
                'taspen-muted': '#6B7280',
                'taspen-success': '#2E9B62',
                'taspen-warning': '#E8A317',
                'taspen-danger': '#D64545',
            }
        },
    },

    plugins: [forms],
};
