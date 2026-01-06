/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./app/**/*.php",
        "./public/**/*.js",
    ],
    theme: {
        extend: {
            colors: {
                brand: {
                    50: "#f0f9f9",
                    100: "#d9f2f1",
                    200: "#b7e6e4",
                    300: "#87d4d0",
                    400: "#56bbb6",
                    500: "#528b89", // Primary brand color
                    600: "#446b6a",
                    700: "#3a5756",
                    800: "#324947",
                    900: "#2d3e3d",
                },
                // Semantic colors for better maintenance
                success: {
                    50: "#f0fdf4",
                    500: "#22c55e",
                    600: "#16a34a",
                },
                warning: {
                    50: "#fffbeb",
                    500: "#f59e0b",
                    600: "#d97706",
                },
                danger: {
                    50: "#fef2f2",
                    500: "#ef4444",
                    600: "#dc2626",
                },
            },
            fontFamily: {
                fredoka: ['"Fredoka One"', "cursive"],
                nunito: ['"Nunito"', "sans-serif"],
                instrument: [
                    '"Instrument Sans"',
                    "ui-sans-serif",
                    "system-ui",
                    "sans-serif",
                ],
            },
            // Custom shadows for consistency
            boxShadow: {
                gentle: "0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06)",
                "gentle-lg":
                    "0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)",
            },
        },
    },
    plugins: [],
};
