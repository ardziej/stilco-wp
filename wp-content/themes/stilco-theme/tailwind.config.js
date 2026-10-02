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
                    'accent': '#C85A41', // Terakota / Rdzawy Pomarańcz
                    'secondary': '#C85A41', // Terakota zastępująca dawną szałwię
                    'sand': '#F4EFEA' // Ciepły beż / piaskowy (Soft Beige)
                }
            },
            fontFamily: {
                sans: ['Inter', 'sans-serif'],
                serif: ['Playfair Display', 'serif'],
                display: ['Outfit', 'sans-serif']
            },
            keyframes: {
                fadeInUp: {
                    '0%': { opacity: '0', transform: 'translateY(30px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                fadeInDown: {
                    '0%': { opacity: '0', transform: 'translateY(-30px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                fadeInLeft: {
                    '0%': { opacity: '0', transform: 'translateX(-30px)' },
                    '100%': { opacity: '1', transform: 'translateX(0)' },
                },
                fadeInRight: {
                    '0%': { opacity: '0', transform: 'translateX(30px)' },
                    '100%': { opacity: '1', transform: 'translateX(0)' },
                },
                zoomIn: {
                    '0%': { opacity: '0', transform: 'scale(0.95)' },
                    '100%': { opacity: '1', transform: 'scale(1)' },
                }
            },
            // Colours for the `prose-stilco` modifier used by legal pages, plain pages and blog posts.
            typography: {
                stilco: {
                    css: {
                        '--tw-prose-body': 'rgb(33 37 41 / 0.8)',
                        '--tw-prose-headings': '#212529',
                        '--tw-prose-lead': 'rgb(33 37 41 / 0.8)',
                        '--tw-prose-links': '#C85A41',
                        '--tw-prose-bold': '#212529',
                        '--tw-prose-counters': '#C85A41',
                        '--tw-prose-bullets': '#C85A41',
                        '--tw-prose-hr': 'rgb(33 37 41 / 0.1)',
                        '--tw-prose-quotes': '#212529',
                        '--tw-prose-quote-borders': '#C85A41',
                        '--tw-prose-captions': 'rgb(33 37 41 / 0.6)',
                    },
                },
            },
            animation: {
                'fade-in-up': 'fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                'fade-in-down': 'fadeInDown 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                'fade-in-left': 'fadeInLeft 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                'fade-in-right': 'fadeInRight 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards',
                'zoom-in': 'zoomIn 1s cubic-bezier(0.16, 1, 0.3, 1) forwards',
            }
        },
    },
    plugins: [require('@tailwindcss/typography')],
}
