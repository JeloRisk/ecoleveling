module.exports = {
    purge: ["./index.html", "./src/**/*.{vue,js,ts,jsx,tsx}"],
    darkMode: false, // or 'media' or 'class'
    theme: {
        extend: {
            fontFamily: {
                sans: ["Poppins", "sans-serif"],
            },
        },
    },
    variants: {
        extend: {},
    },
    plugins: [require("flowbite/plugin")],

    content: ["./node_modules/flowbite/**/*.js"],
};
