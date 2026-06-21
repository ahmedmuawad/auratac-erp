/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            fontFamily: {
                // Arabic primary (Cairo) + Latin reference (Roboto) — Material Design type system
                sans: ['Cairo', 'Roboto', 'system-ui', 'sans-serif'],
                cairo: ['Cairo', 'sans-serif'],
                roboto: ['Roboto', 'sans-serif'],
            },
            // Material Design 3 color roles (mapped to CSS variables in app.css)
            colors: {
                primary: 'var(--md-primary)',
                'on-primary': 'var(--md-on-primary)',
                'primary-container': 'var(--md-primary-container)',
                'on-primary-container': 'var(--md-on-primary-container)',
                secondary: 'var(--md-secondary)',
                'on-secondary': 'var(--md-on-secondary)',
                'secondary-container': 'var(--md-secondary-container)',
                'on-secondary-container': 'var(--md-on-secondary-container)',
                tertiary: 'var(--md-tertiary)',
                'on-tertiary': 'var(--md-on-tertiary)',
                'tertiary-container': 'var(--md-tertiary-container)',
                'on-tertiary-container': 'var(--md-on-tertiary-container)',
                error: 'var(--md-error)',
                'on-error': 'var(--md-on-error)',
                'error-container': 'var(--md-error-container)',
                'on-error-container': 'var(--md-on-error-container)',
                success: 'var(--md-success)',
                'success-container': 'var(--md-success-container)',
                'on-success-container': 'var(--md-on-success-container)',
                warning: 'var(--md-warning)',
                'warning-container': 'var(--md-warning-container)',
                'on-warning-container': 'var(--md-on-warning-container)',
                background: 'var(--md-background)',
                'on-background': 'var(--md-on-background)',
                surface: 'var(--md-surface)',
                'on-surface': 'var(--md-on-surface)',
                'surface-variant': 'var(--md-surface-variant)',
                'on-surface-variant': 'var(--md-on-surface-variant)',
                'surface-low': 'var(--md-surface-container-low)',
                'surface-container': 'var(--md-surface-container)',
                'surface-high': 'var(--md-surface-container-high)',
                'surface-highest': 'var(--md-surface-container-highest)',
                outline: 'var(--md-outline)',
                'outline-variant': 'var(--md-outline-variant)',
                // Aura Tac onyx (dark navigation surfaces)
                onyx: 'var(--md-onyx)',
                'onyx-surface': 'var(--md-onyx-surface)',
                'on-onyx': 'var(--md-on-onyx)',
                'on-onyx-variant': 'var(--md-on-onyx-variant)',
            },
            // Material Design 3 shape scale
            borderRadius: {
                'md-xs': '4px',
                'md-sm': '8px',
                'md-md': '12px',
                'md-lg': '16px',
                'md-xl': '28px',
            },
            // Material Design 3 elevation (tonal shadows)
            boxShadow: {
                'md-1': '0 1px 2px rgba(0,0,0,0.30), 0 1px 3px 1px rgba(0,0,0,0.10)',
                'md-2': '0 1px 2px rgba(0,0,0,0.30), 0 2px 6px 2px rgba(0,0,0,0.12)',
                'md-3': '0 1px 3px rgba(0,0,0,0.30), 0 4px 8px 3px rgba(0,0,0,0.12)',
                'md-4': '0 2px 3px rgba(0,0,0,0.30), 0 6px 10px 4px rgba(0,0,0,0.12)',
            },
        },
    },
    plugins: [],
}
