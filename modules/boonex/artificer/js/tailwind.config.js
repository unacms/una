module.exports = {
  purge: {
    enabled: true,
    content: [
        './js/*.js',
        './template/*.html',
        './data/template/**/*.html',
    ],
    safelist: [
        'w-8', 'w-10', 'w-24', 'w-48', 'w-4/6', 
        'h-8', 'h-10', 'h-24', 'h-48',
        '-m-2',
        'text-red-400', 
        'bx-def-margin', 'bx-def-margin-left', 'bx-def-margin-left-auto', 'bx-def-margin-right', 'bx-def-margin-top', 'bx-def-margin-top-auto', 'bx-def-margin-bottom',
        'bx-def-margin-sec', 'bx-def-margin-sec-left', 'bx-def-margin-sec-left-auto', 'bx-def-margin-sec-right', 'bx-def-margin-sec-top', 'bx-def-margin-sec-top-auto', 'bx-def-margin-sec-bottom',
        'bx-def-padding', 'bx-def-padding-left', 'bx-def-padding-left-auto', 'bx-def-padding-right', 'bx-def-padding-top', 'bx-def-padding-top-auto', 'bx-def-padding-bottom',
        'bx-def-padding-sec', 'bx-def-padding-sec-left', 'bx-def-padding-sec-left-auto', 'bx-def-padding-sec-right', 'bx-def-padding-sec-top', 'bx-def-padding-sec-top-auto', 'bx-def-padding-sec-bottom',
        'bx-def-a-colored',
        'bx-form-required', 'bx-form-warn',
        'bx-popup-full-screen', 'bx-popup-fog',
        'bx-informer-msg-info', 'bx-informer-msg-alert', 'bx-informer-msg-error',
    ]
  },
  darkMode: 'media', // false or 'media' or 'class'
  theme: {
    fontFamily: {
        'inter': ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', '"Segoe UI"', 'Roboto', '"Helvetica Neue"', 'Arial', '"Noto Sans"', 'sans-serif', '"Apple Color Emoji"', '"Segoe UI Emoji"', '"Segoe UI Symbol"', '"Noto Color Emoji"'],
    },
    extend: {
        width: {
            46: '11.5rem',
            112: '28rem',
            128: '32rem',
            144: '36rem'
        },
        minWidth: {
            88: '22rem'
        },
        height: {
            112: '28rem',
            128: '32rem',
            144: '36rem'
        },
        lineHeight: {
            11: '2.75rem',
            12: '3rem',
            13: '3.25rem',
            14: '3.5rem',
            15: '3.75rem',
            16: '4rem',
        },
        flex: {
            2: '2 2 0%',
        },
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
