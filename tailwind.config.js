import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            // ─── Typography ───────────────────────────────────────────────
            // Plus Jakarta Sans sebagai font utama dengan system fallback.
            // MASTER.md §5 — tidak memuat font eksternal di Fase 1.
            fontFamily: {
                sans: [
                    'Plus Jakarta Sans',
                    'Inter',
                    ...defaultTheme.fontFamily.sans,
                ],
            },

            // ─── Type Scale ────────────────────────────────────────────────
            // MASTER.md §5. Override line-height bawaan Tailwind sesuai token.
            // Default Tailwind sizes di luar range ini tetap tersedia.
            fontSize: {
                xs:   ['0.75rem',   { lineHeight: '1rem' }],
                sm:   ['0.875rem',  { lineHeight: '1.25rem' }],
                base: ['1rem',      { lineHeight: '1.625rem' }],
                lg:   ['1.125rem',  { lineHeight: '1.75rem' }],
                xl:   ['1.25rem',   { lineHeight: '1.875rem' }],
                '2xl':['1.5rem',    { lineHeight: '2rem' }],
                '3xl':['1.875rem',  { lineHeight: '2.375rem' }],
                '4xl':['2.25rem',   { lineHeight: '2.75rem' }],
            },

            // ─── Colors ────────────────────────────────────────────────────
            // Semua token mereferensikan CSS custom properties di :root.
            // Tidak ada raw hex di sini — satu sumber kebenaran di app.css.
            // MASTER.md §4. Tidak menggunakan opacity modifier Tailwind.
            colors: {
                // Primary — forest green
                primary: {
                    DEFAULT: 'var(--color-primary)',
                    dark:    'var(--color-primary-dark)',
                    soft:    'var(--color-primary-soft)',
                },
                'on-primary': 'var(--color-on-primary)',

                // Secondary — medium green
                secondary: 'var(--color-secondary)',

                // Logo green — aksen kecil saja
                'logo-green': 'var(--color-logo-green)',

                // Accent — muted gold
                accent: {
                    DEFAULT: 'var(--color-accent)',
                    soft:    'var(--color-accent-soft)',
                },

                // Surfaces — warm neutral
                background: 'var(--color-background)',
                surface: {
                    DEFAULT: 'var(--color-surface)',
                    muted:   'var(--color-surface-muted)',
                },

                // Text
                text: {
                    strong:  'var(--color-text-strong)',
                    DEFAULT: 'var(--color-text)',
                    muted:   'var(--color-text-muted)',
                },

                // Borders
                border: {
                    DEFAULT: 'var(--color-border)',
                    strong:  'var(--color-border-strong)',
                },

                // Semantic status — MASTER.md §4
                success: {
                    DEFAULT: 'var(--color-success)',
                    soft:    'var(--color-success-soft)',
                },
                warning: {
                    DEFAULT: 'var(--color-warning)',
                    soft:    'var(--color-warning-soft)',
                },
                error: {
                    DEFAULT: 'var(--color-error)',
                    soft:    'var(--color-error-soft)',
                },
                info: {
                    DEFAULT: 'var(--color-info)',
                    soft:    'var(--color-info-soft)',
                },

                // Focus ring — accessibility
                'focus-ring': 'var(--color-focus-ring)',
            },

            // ─── Max Width (container tokens) ──────────────────────────────
            // MASTER.md §6. Spacing bawaan Tailwind tidak diubah.
            maxWidth: {
                'container-page':    'var(--container-page)',
                'container-content': 'var(--container-content)',
                'container-narrow':  'var(--container-narrow)',
                'container-wide':    'var(--container-wide)',
            },

            // ─── Border Radius ─────────────────────────────────────────────
            // MASTER.md §7. Radius umum maksimal 8px (DESIGN.md §15).
            borderRadius: {
                sm: 'var(--radius-sm)',
                md: 'var(--radius-md)',
                lg: 'var(--radius-lg)',
            },

            // ─── Box Shadow ────────────────────────────────────────────────
            // MASTER.md §7. Shadow sangat minimal — border & background
            // diutamakan sebelum shadow (DESIGN.md §15).
            boxShadow: {
                xs:   'var(--shadow-xs)',
                sm:   'var(--shadow-sm)',
                none: 'none',
            },

            // ─── Z-Index ───────────────────────────────────────────────────
            // MASTER.md §7. Layer scale untuk header, dropdown, modal, dll.
            zIndex: {
                base:        'var(--z-base)',
                raised:      'var(--z-raised)',
                header:      'var(--z-header)',
                dropdown:    'var(--z-dropdown)',
                modal:       'var(--z-modal)',
                'skip-link': 'var(--z-skip-link)',
            },

            // ─── Transition Duration ───────────────────────────────────────
            // MASTER.md §8. Motion minimal dan fungsional (DESIGN.md §33).
            transitionDuration: {
                fast: 'var(--duration-fast)',
                base: 'var(--duration-base)',
                slow: 'var(--duration-slow)',
            },

            // ─── Transition Timing ─────────────────────────────────────────
            transitionTimingFunction: {
                standard: 'var(--ease-standard)',
            },
        },
    },
    plugins: [],
};

