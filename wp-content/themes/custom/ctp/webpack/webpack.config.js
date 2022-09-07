const webpack   = require('webpack');
const path      = require('path');
const uglify    = require('uglifyjs-webpack-plugin');
const extract   = require('extract-text-webpack-plugin');
const cleanup   = require('webpack-extraneous-file-cleanup-plugin');
const sync      = require('browser-sync-webpack-plugin');

/**
 * When the --watch argument is passed we increase build performance by opting
 * out of optimizations like minification and source maps
 */
const optimize = process.argv.indexOf( '--watch' ) === -1;

const config = {
	devtool: optimize ? 'source-map' : '',
	entry: {
		'app-styles':       './styles/app.scss',
		'editor-styles':    './styles/editor.scss',
		'admin-mg':         './styles/wordpress/admin-schemes/mindgrub.scss',
		'admin-client':     './styles/wordpress/admin-schemes/client.scss',
		'admin-styles':     './styles/admin.scss',
		'print-styles':     './styles/print.scss',
		'app-scripts':      './scripts/app.js'
	},
	plugins: [
		new sync({
			host:       'localhost',
			port:       '3791',
			proxy:      'check-to-protect.lndo.site',
			ghostMode:  false,
			notify:     true,
		}),
		new extract({
			filename: '../styles/[name].min.css'
		}),
		new cleanup({
			extensions: ['.js'],
			minBytes: optimize ? 1000 : 3000
		}),
		new webpack.ProvidePlugin({
			Util: 'exports-loader?Util!bootstrap/js/dist/util'
		})
	],
	output: {
		filename: '[name].min.js',
		path: path.resolve(__dirname, '../dist/scripts'),
	},
	externals: {
		jquery: '$'
	},
	module: {
		rules: [
			{
				test: /\.js$/,
				exclude: /node_modules/,
				use: {
					loader: 'babel-loader',
					options: {
						presets: [
							'babel-preset-env'
						]
					}
				}
			},
			{
				test: /\.scss$/,
				use: extract.extract({
					use: [
						{
							loader: 'css-loader',
							options: {
								minimize: optimize,
								sourceMap: optimize
							}
						},
						{
							loader: 'postcss-loader',
							options: {
								sourceMap: optimize
							}
						},
						{
							loader: 'sass-loader',
							options: {
								sourceMap: optimize
							}
						}
					]
				})
			},
			{
				test: /\.(woff(2)?|ttf|eot|svg)(\?v=\d+\.\d+\.\d+)?$/,
				include: /(fonts|node_modules)/,
				loader: 'file-loader',
				options: {
					name: '../fonts/[name].[ext]'
				}
			},
			{
				test: /\.(png|jpg|jpeg|gif|ico|svg)$/,
				include: /images/,
				loader: 'file-loader',
				options: {
					name: '../images/[name].[ext]'
				}
			}
		]
	}
};

// if the NPM run script doesn't have the --watch param present, no source maps
if(optimize) {
	config.plugins.push(
		new uglify({ sourceMap: false })
	);
}

/**
 * Public API
 */
module.exports = config;
