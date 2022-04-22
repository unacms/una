Installation manual: https://tailwindcss.com/docs/installation

To compile use the following command:
NODE_ENV=production npx tailwindcss -i ./plugins_public/tailwind/css/tailwind.css -c ./plugins_public/tailwind/js/tailwind.config.js -o ./plugins_public/tailwind/css/tailwind.min.css --minify
It should be called from UNA's root folder.

For custom styles only:
To compile use the following command:
NODE_ENV=production npx tailwindcss -i ./plugins_public/tailwind/css/tailwind_custom.css -c ./plugins_public/tailwind/js/tailwind_custom.config.js -o ./plugins_public/tailwind/css/tailwind_custom.min.css --minify
It should be called from UNA's root folder.