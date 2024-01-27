/**
 * SPDX-FileCopyrightText: 2018 John Molakvoæ <skjnldsv@protonmail.com>
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

import { generateFilePath } from '@nextcloud/router'

import Vue from 'vue'
import Upload from './Views/Upload.vue'
import VueRouter from 'vue-router'
import Preview from './Views/Preview.vue'
import App from './App.vue'

Vue.use(VueRouter)

const routes = [
	{ path: '/', component: Upload },
	{
		name: 'Preview',
		path: '/preview',
		component: Preview,
		props: true,
	},
]

const router = new VueRouter({
	routes,
})

// eslint-disable-next-line
__webpack_public_path__ = generateFilePath(appName, '', 'js/')

Vue.mixin({ methods: { t, n } })
Vue.prototype.OC = window.OC
Vue.prototype.OCA = window.OCA

export default new Vue({
	el: '#content',
	render: h => h(App),
	router,
})
