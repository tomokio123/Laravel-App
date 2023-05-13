const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        //元々はpurgeだった。php artisan serveだけだと反映されないので、npm run watchしないといけない。ホットリロード的なもの
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                white: {
                    300: "#F8F8F8",
                    500: "#fff",
                },
                gray: {
                    100: "#EEEFF2",
                    400: "#AFB5C0",
                    500: "#DDDDDD",
                },
                red: {
                    500: "#ef4444",
                },
                blue: {
                    500: "#3b82f6",
                },
            },
        },
    },

    plugins: [require('@tailwindcss/forms')],
};
