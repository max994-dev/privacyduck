/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './src/**/*.php',
    './admin/**/*.php',
    './assets/**/*.js',
    './index.php',
  ],
  theme: {
    extend: {
      // Named font families so `font-roboto`, `font-jakarta`, etc. work as
      // utility classes instead of `style="font-family: ..."` everywhere.
      fontFamily: {
        roboto:  ['Roboto', 'ui-sans-serif', 'system-ui', 'sans-serif'],
        jakarta: ['"Plus Jakarta Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
        alatsi:  ['Alatsi', 'ui-sans-serif', 'system-ui', 'sans-serif'],
        manrope: ['Manrope', 'ui-sans-serif', 'system-ui', 'sans-serif'],
        dmsans:  ['"DM Sans"', 'ui-sans-serif', 'system-ui', 'sans-serif'],
        poppins: ['Poppins', 'ui-sans-serif', 'system-ui', 'sans-serif'],
        inter:   ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
      },
      // Brand color so `text-brand`, `bg-brand`, `border-brand`, `ring-brand`
      // all work instead of `style="color: #24A556;"` or
      // `class="text-[#24A556]"` everywhere.
      colors: {
        brand: {
          DEFAULT: '#24A556',
          50:  '#E8F6EE',
          100: '#C5E8D2',
          500: '#24A556',
          600: '#1E8C49',
          700: '#176E39',
        },
      },
    },
  },
  plugins: [require('flowbite/plugin')],
};
