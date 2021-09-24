module.exports = {
  purge: [],
  darkMode: 'media', // false or 'media' or 'class'
  theme: {
    fontFamily: {
        'inter': ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', '"Segoe UI"', 'Roboto', '"Helvetica Neue"', 'Arial', '"Noto Sans"', 'sans-serif', '"Apple Color Emoji"', '"Segoe UI Emoji"', '"Segoe UI Symbol"', '"Noto Color Emoji"'],
    },
    extend: {
        animation: {
          goo: "goo 8s infinite",
        },
        keyframes: {
          goo: {
            "0%": {
              transform: "translate(0px, 0px) scale(1)",
            },
            "33%": {
              transform: "translate(30px, -50px) scale(1.2)",
            },
            "66%": {
              transform: "translate(-20px, 20px) scale(0.8)",
            },
            "100%": {
              transform: "translate(0px, 0px) scale(1)",
            },
          },
        },
    },
  },
  variants: {
    extend: {
        margin: ['first', 'last'],
        padding: ['first', 'last'],
        ringColor: ['hover', 'active'],
    },
  },
  plugins: [],
}
