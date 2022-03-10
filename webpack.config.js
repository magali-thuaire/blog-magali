// Generated using webpack-cli https://github.com/webpack/webpack-cli

const path = require("path");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const CssMinimizerPlugin = require("css-minimizer-webpack-plugin");
const CopyPlugin = require("copy-webpack-plugin");
const { CleanWebpackPlugin } = require('clean-webpack-plugin');

const config = {
    entry: "./assets/app.js",
    output: {
        path: path.resolve(__dirname, "public/build"),
        filename: 'app.js'
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: 'app.css'
        }),
        new CopyPlugin({
            patterns: [
              { from: "./assets/images", to: "images" },
              { from: "./assets/files", to: "files" },
            ],
        }),
        new CleanWebpackPlugin(),
    ],
    module: {
        rules: [
    {
        test: /\.(js|jsx)$/i,
        loader: "babel-loader",
    },
    {
        test: /\.css$/i,
        use: [MiniCssExtractPlugin.loader, "css-loader"],
    },
    {
        test: /\.s[ac]ss$/i,
        use: [
        MiniCssExtractPlugin.loader,
        {
            loader: 'css-loader',
            options: {
                url: false
            }
        },
        'sass-loader'
        ]
    },
    {
        test: /\.(svg|eot|woff|woff2|ttf)$/,
        type: 'asset/resource',
        generator: {
            filename: 'fonts/[name][ext]',
        },
    }
    ],
    },
    optimization: {
        minimizer: [
            `...`,
            new CssMinimizerPlugin(),
        ],
    },
};

module.exports = () => {
    config.mode = "production";
    return config;
};
