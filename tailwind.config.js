/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
        './resources/css/**/*.css',
        './app/Filament/**/*.php',
        './vendor/filament/**/*.blade.php',
        './resources/**/*.blade.php',
    ],
    theme: {
        extend: {},
    },
    plugins: [],
};
