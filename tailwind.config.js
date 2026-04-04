/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './views/**/*.php',
    './public/**/*.php',
    './app/**/*.php',
    './resources/**/*.css',
  ],
  safelist: [
    // Grade badges (dynamic PHP)
    'badge-A','badge-B','badge-C','badge-D',
    // Dynamic colors used in PHP
    'bg-brand-500','bg-violet-500','bg-emerald-500','bg-amber-500','bg-rose-500','bg-cyan-500',
    'text-brand-600','text-violet-600','text-emerald-600','text-amber-600','text-rose-600',
    'text-brand-400','text-violet-400','text-emerald-400','text-amber-400','text-rose-400',
    'text-brand-600','text-emerald-600','text-amber-600','text-red-600',
    'bg-brand-50','bg-violet-50','bg-emerald-50','bg-amber-50','bg-rose-50',
    'bg-brand-900/30','bg-violet-900/30','bg-emerald-900/30','bg-amber-900/30','bg-rose-900/30',
    // Avatar colors (dynamic)
    'avatar-blue','avatar-violet','avatar-green','avatar-amber',
    // Animation delays (dynamic style)
    { pattern: /animation-delay-\d+/ },
    // Progress fill colors
    'bg-emerald-500','bg-brand-500','bg-amber-500','bg-red-500',
  ],
  darkMode: 'class',
  theme: {
    extend: {
      colors: {
        brand: {
          50:  '#EFF6FF',
          100: '#DBEAFE',
          200: '#BFDBFE',
          400: '#60A5FA',
          500: '#3B82F6',
          600: '#2563EB',
          700: '#1D4ED8',
          800: '#1E40AF',
          900: '#1E3A8A',
          950: '#172554',
        },
        dark: {
          bg:      '#0D1117',
          surface: '#161B27',
          card:    '#1E2433',
          border:  '#2A3147',
          hover:   '#252C3E',
          muted:   '#374162',
          text:    '#94A3B8',
        },
      },
      fontFamily: {
        sans:    ['"DM Sans"', 'system-ui', 'sans-serif'],
        display: ['"Bricolage Grotesque"', 'system-ui', 'sans-serif'],
        mono:    ['"JetBrains Mono"', 'monospace'],
      },
      boxShadow: {
        'glow':    '0 0 24px rgba(37,99,235,0.2)',
        'card':    '0 1px 3px rgba(0,0,0,.06),0 1px 2px rgba(0,0,0,.04)',
        'card-md': '0 4px 20px rgba(0,0,0,.08)',
        'card-lg': '0 10px 40px rgba(0,0,0,.12)',
        'sidebar': '4px 0 30px rgba(0,0,0,.15)',
      },
      animation: {
        'fade-up':    'fadeUp 0.45s cubic-bezier(0.16,1,0.3,1) both',
        'fade-in':    'fadeIn 0.3s ease both',
        'float':      'float 6s ease-in-out infinite',
        'pulse-slow': 'pulse 3s cubic-bezier(0.4,0,0.6,1) infinite',
      },
      keyframes: {
        fadeUp: { from:{opacity:'0',transform:'translateY(14px)'}, to:{opacity:'1',transform:'translateY(0)'} },
        fadeIn: { from:{opacity:'0'}, to:{opacity:'1'} },
        float:  { '0%,100%':{transform:'translateY(0)'}, '50%':{transform:'translateY(-10px)'} },
      },
    },
  },
  plugins: [],
}
