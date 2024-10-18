const mix = require('laravel-mix');

mix.webpackConfig({
    output: {
        chunkFilename: 'chunks/[name].js',
        publicPath: '/',
    }
});

mix.js('resources/js/app.js', 'public/js').vue();
