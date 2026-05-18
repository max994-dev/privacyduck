/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './src/**/*.php',
    './admin/**/*.php',
    './assets/**/*.js',
    './index.php',
  ],
  theme: {
    extend: {},
  },
  plugins: [require('flowbite/plugin')],
};
