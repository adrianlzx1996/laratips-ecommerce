const defaultTheme = require("tailwindcss/defaultTheme");

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/admin/**/*.blade.php",
        "./resources/js/admin/**/*.vue",
        "./node_modules/tw-elements/dist/js/**/*.js",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Nunito", ...defaultTheme.fontFamily.sans],
            },
            animation: {
                'progress': 'progress 3s ease-in-out infinite',
            },
            keyframes: {
                progress: {
                    '0%, 100%': {width: '0%', marginLeft: '0%'},
                    '20%': {marginLeft: '0%'},
                    '100%': {width: '100%', marginLeft: '100%'},
                }
            },
        },
    },

    plugins: [
        require("@tailwindcss/forms"),
        require('tw-elements/dist/plugin')
    ],
};
