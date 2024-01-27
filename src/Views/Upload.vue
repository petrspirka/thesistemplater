<template>
	<!--
    SPDX-FileCopyrightText: Petr Spirka <petrspirka@gmail.com>
    SPDX-License-Identifier: AGPL-3.0-or-later
    -->
	<div id="content" class="app-thesistemplater">
		<FileDropInput text="Upload files" @uploaded="onFileUpload" />
	</div>
</template>

<script>
import FileDropInput from '../Components/FileDropInput.vue'
import { showError } from '@nextcloud/dialogs'

import '@nextcloud/dialogs/style.css'
import { generateUrl } from '@nextcloud/router'
import axios from '@nextcloud/axios'

export default {
	name: 'Upload',
	components: {
		FileDropInput,
	},
	data() {
		return {
			notes: [],
			currentNoteId: null,
			updating: false,
			loading: true,
		}
	},
	methods: {
		async onFileUpload(event) {
			const file = event[0]
			this.sendData(file)
		},
		async sendData(file) {
			const formData = new FormData()
			formData.append('file', file, file.name)
			let response
			try {
				response = await axios.post(generateUrl('/apps/thesistemplater/api/0.1/student_file/parse'), formData)
			} catch (e) {
				showError('Could not upload file: ' + e.message)
				console.error(e)
				return
			}
			if (response.status === 200) {
				this.$router.push({ name: 'Preview', params: { parsedStudents: response.data.data } })
			}
		},
	},
}
</script>
<style scoped>
	#app-content > div {
		width: 100%;
		height: 100%;
		padding: 20px;
		display: flex;
		flex-direction: column;
		flex-grow: 1;
	}

	input[type='text'] {
		width: 100%;
	}

	textarea {
		flex-grow: 1;
		width: 100%;
	}

	#content {
		width: 100%;
		margin-left: 0px !important;
		margin-bottom: 0px !important;
	}
</style>
