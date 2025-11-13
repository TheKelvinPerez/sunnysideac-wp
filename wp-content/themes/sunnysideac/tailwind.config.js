/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./src/**/*.{js,ts,jsx,tsx}",
    "./src/css/**/*.{css}",
    "./*.php",
    "./**/*.php",
    "./template-parts/**/*.php",
    "./inc/**/*.php",
  ],
  prefix: "",
  theme: {
    extend: {},
  },
  plugins: [],
}