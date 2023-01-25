let mix = require('laravel-mix');

mix.setPublicPath('public');

mix.js('resources/js/app.js', 'public')
    .postCss("resources/css/app.css", "public", [
        require("tailwindcss"),
    ])
    .options({
        terser: {
            extractComments: false,
        },
    })
    .disableNotifications();


