/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        'primary': '#3B82F6', // Blue
        'secondary': '#10B981', // Green
        'accent': '#8B5CF6', // Purple
        'danger': '#EF4444', // Red
        'warning': '#F59E0B', // Amber
        'info': '#3B82F6', // Blue
        'dark': '#1F2937', // Gray-800
      },
    },
  },
  plugins: [],
}
