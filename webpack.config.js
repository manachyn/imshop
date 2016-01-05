var webpack = require('webpack');
var path = require('path');
module.exports = {
    entry: {
        pageA: "./web/assets/pageA",
        pageB: "./web/assets/pageB"
    },
    output: {
        path: path.join(__dirname, "web/js"),
        filename: "[name].bundle.js",
        chunkFilename: "[id].chunk.js"
    },
    //resolve: {
    //    root: path.resolve(__dirname),
    //    alias: {
    //        'im/imshop': '/home/ubuntu/www/imshop/themes/imshop/src/assets/js'
    //    },
    //    extensions: ['', '.js', '.jsx']
    //},
    //plugins: [
    //    new webpack.ProvidePlugin({
    //        $: "jquery",
    //        jQuery: "jquery"
    //    })
    //]
};