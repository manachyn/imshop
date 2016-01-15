var webpack = require('webpack');
var path = require('path');
var ExtractTextPlugin = require("extract-text-webpack-plugin");
module.exports = {
    entry: {
        home: "./web/assets/home",
        main: "./web/assets/main"
    },
    output: {
        path: path.join(__dirname, "web/compiled-assets"),
        filename: "[name].bundle.js",
        chunkFilename: "[id].chunk.js",
        publicPath: "/compiled-assets/"
    },
    module: {
        loaders: [
            {
                test: /\.jsx?$/,
                exclude: /(node_modules|bower_components)/,
                loader: 'babel',
                query: {
                    presets: ['react', 'es2015']
                }
            },
            { test: /\.css$/, loader: ExtractTextPlugin.extract("style-loader", "css-loader") },
            { test: /\.woff(2)?(\?v=[0-9]\.[0-9]\.[0-9])?$/, loader: "url-loader?limit=10000&minetype=application/font-woff" },
            { test: /\.(ttf|eot|svg)(\?v=[0-9]\.[0-9]\.[0-9])?$/, loader: "file-loader" },
            { test: /\.(jpe?g|png|gif|svg)$/i, loader: "file-loader" }
        ]
    },
    plugins: [
        new ExtractTextPlugin("[name].css")
    ],
    resolve: {
        //root: path.resolve(__dirname),
        alias: {
            'im/search': '/home/ubuntu/www/imshop/modules/search/src/assets/js'
        },
        extensions: ['', '.js', '.jsx']
    },
    //plugins: [
    //    new webpack.ProvidePlugin({
    //        $: "jquery",
    //        jQuery: "jquery"
    //    })
    //]
};