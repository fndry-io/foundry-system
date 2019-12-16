var HtmlWebpackPlugin = require('html-webpack-plugin');


module.exports = {
    // options...

    publicPath: '',
    outputDir: './dist',
    indexPath: 'index.html',
    devServer: {
        proxy: {
            '^/api': {
                target: 'http://localhost:80/'
            }
        }
    },
    lintOnSave: false,
    runtimeCompiler: true,
    configureWebpack: {
        plugins: [
            new HtmlWebpackPlugin({
                template: './demo/index.html',
                inject: true
            }),
        ]
    }
};