/** @type {import('tailwindcss').Config} */
export default {
  darkMode: 'class', // <--- ESTO ES VITAL
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}