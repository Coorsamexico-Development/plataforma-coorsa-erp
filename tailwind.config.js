const defaultTheme = require("tailwindcss/defaultTheme");

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./vendor/laravel/jetstream/**/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.vue",
        "./node_modules/flowbite/**/*.js",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Nunito", ...defaultTheme.fontFamily.sans],
            },
            backgroundImage: {
                MisQuin: "url('/assets/img/MisionQuinta.png')",
                MisCoord: "url('/assets/img/VisionCoord.png')",
                MisMin: "url('/assets/img/MisionMin.png')",
                MisWhare: "url('/assets/img/MisionWhare.png')",
                VisQuin: "url('/assets/img/VisionQuinta.png')",
                VisCoord: "url('/assets/img/VisionCoord.png')",
                VisMin: "url('/assets/img/VisionMin.png')",
                VisWhare: "url('/assets/img/VisionWhare.png')",
            },
        },
    },

    plugins: [
        require("@tailwindcss/forms"),
        require("@tailwindcss/typography"),
        require("flowbite/plugin"),
    ],
};
