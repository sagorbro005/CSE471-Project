import { fontFamily as _fontFamily } from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export const content = [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
    './resources/js/**/*.vue',
    './resources/js/**/*.js',
    './resources/js/**/*.ts',
];
export const safelist = [
    // Border colors
    'border-blue-500',
    'border-purple-500',
    'border-green-500',
    'border-orange-500',
    'border-red-500',
    // Text colors
    'text-blue-500',
    'text-purple-500',
    'text-green-500',
    'text-orange-500',
    'text-red-500',
    // Background colors
    'bg-blue-500',
    'bg-purple-500',
    'bg-green-500',
    'bg-orange-500',
    'bg-red-500',
    // Hover states
    'hover:bg-blue-500',
    'hover:bg-purple-500',
    'hover:bg-green-500',
    'hover:bg-orange-500',
    'hover:bg-red-500',
    'hover:text-blue-500',
    'hover:text-purple-500',
    'hover:text-green-500',
    'hover:text-orange-500',
    'hover:text-red-500',
    // Group hover states
    'group-hover:text-blue-500',
    'group-hover:text-purple-500',
    'group-hover:text-green-500',
    'group-hover:text-orange-500',
    'group-hover:text-red-500',
];
export const theme = {
    extend: {
        fontFamily: {
            sans: ['Figtree', ..._fontFamily.sans],
        },
    },
};
export const plugins = [require('@tailwindcss/forms')];
