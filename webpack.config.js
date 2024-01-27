// SPDX-FileCopyrightText: Petr Spirka <petrspirka@gmail.com>
// SPDX-License-Identifier: AGPL-3.0-or-later
const webpackConfig = require('@nextcloud/webpack-vue-config')
const path = require('path')

webpackConfig.entry = {
	upload: path.join(__dirname, 'src', 'upload.js'),
	settings: path.join(__dirname, 'src', 'settings.js'),
}

module.exports = webpackConfig
