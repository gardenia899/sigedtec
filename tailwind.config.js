import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/**
 * Design System SIGEDTEC v1.0 — SDD §20.2/§20.3.
 * Único punto de cambio de la identidad visual (§20.14):
 * ninguna vista debe declarar colores fuera de estos tokens.
 *
 * @type {import('tailwindcss').Config}
 */
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
                /* primary — acciones, enlaces, selección activa */
                primary: {
                    50: '#EFF5FF',
                    100: '#DBE8FE',
                    200: '#BFD6FE',
                    300: '#93BAFD',
                    400: '#6096FA',
                    500: '#3B76F6',
                    600: '#2563EB',
                    700: '#1D4ED8',
                    800: '#1E40AF',
                    900: '#1E3A8A',
                    950: '#0B1B3A',
                },

                /* navy — barra lateral, hero y pantallas de autenticación */
                navy: {
                    700: '#123A6B',
                    800: '#0C2750',
                    900: '#081A34',
                    950: '#050F1F',
                },

                /* superficies y texto */
                page: '#F1F5FB',
                surface: '#FFFFFF',
                ink: '#0F172A',
                muted: '#64748B',
                line: '#E2E8F0',
            },

            boxShadow: {
                card: '0 1px 2px 0 rgba(15, 23, 42, 0.06)',
                panel: '0 28px 70px -34px rgba(4, 16, 40, 0.85)',
                nav: '0 12px 26px -14px rgba(29, 111, 242, 0.9)',
            },
        },
    },

    plugins: [forms],
};
