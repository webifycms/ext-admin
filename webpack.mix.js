const mix = require('laravel-mix')

mix.js("resources/assets/js/app.js", "js")
    .postCss("resources/assets/css/app.css", "css", [
        require("tailwindcss"),
    ])
    .setPublicPath('dist')
    .browserSync({
        files: [
            './resources/**/*.js',
            './resources/**/*.css',
            './templates/admin/**/*.php',
            './templates/**/*.php',
        ],
        proxy: 'getonecms.test'
    })
    .copy('node_modules/bootstrap-icons/bootstrap-icons.svg', 'dist/icons/icons.svg')