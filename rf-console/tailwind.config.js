/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './app/**/*.php',
  ],
  theme: {
    extend: {
      colors: {
        navy: {
          950: '#04080F',
          900: '#070F1F',
          800: '#0B1830',
          700: '#122446',
          600: '#1A325F',
        },
        gold: {
          300: '#F8D47A',
          400: '#F5C24D',
          500: '#E5A92E',
          600: '#B8851D',
        },
        signal: {
          300: '#7DE5F2',
          400: '#22D3EE',
          500: '#06B6D4',
        },
        anomaly: {
          400: '#F87171',
          500: '#EF4444',
          600: '#DC2626',
        },
      },
      fontFamily: {
        sans: ['Inter', 'system-ui', 'sans-serif'],
        mono: ['JetBrains Mono', 'ui-monospace', 'monospace'],
      },
      boxShadow: {
        panel: '0 0 0 1px rgba(148,163,184,0.08), 0 12px 32px -16px rgba(0,0,0,0.6)',
        glow: '0 0 24px -4px rgba(34,211,238,0.45)',
      },
    },
  },
  plugins: [],
};
