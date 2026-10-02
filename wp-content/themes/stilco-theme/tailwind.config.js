/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./**/*.php",
        "./assets/js/**/*.js"
    ],
    theme: {
        extend: {
            colors: {
                stilco: {
                    'dark': '#212529', // Grafit główny do napisów
                    'light': '#FAFAFA', // Jasne tło główne (Cream White)
                    'accent': '#A94A33', // Terakota / Rdzawy Pomarańcz
                    'secondary': '#A94A33', // Terakota zastępująca dawną szałwię
                    'accent-hover': '#8E3D2A', // Ciemniejsza terakota dla stanów hover (biały tekst 7.37:1)
                    'sand': '#F4EFEA' // Ciepły beż / piaskowy (Soft Beige)
                }
            },
            fontFamily: {
                sans: ['Inter', 'sans-serif'],
                serif: ['Playfair Display', 'serif'],
                display: ['Outfit', 'sans-serif']
            },
            // Colours for the `prose-stilco` modifier used by legal pages, plain pages and blog posts.
            typography: {
                stilco: {
                    css: {
                        '--tw-prose-body': 'rgb(33 37 41 / 0.8)',
                        '--tw-prose-headings': '#212529',
                        '--tw-prose-lead': 'rgb(33 37 41 / 0.8)',
                        '--tw-prose-links': '#A94A33',
                        '--tw-prose-bold': '#212529',
                        '--tw-prose-counters': '#A94A33',
                        '--tw-prose-bullets': '#A94A33',
                        '--tw-prose-hr': 'rgb(33 37 41 / 0.1)',
                        '--tw-prose-quotes': '#212529',
                        '--tw-prose-quote-borders': '#A94A33',
                        '--tw-prose-captions': 'rgb(33 37 41 / 0.6)',
                    },
                },
            }
        },
    },
    plugins: [require('@tailwindcss/typography')],
}
