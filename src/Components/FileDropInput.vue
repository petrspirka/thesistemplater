<template>
	<!--
    SPDX-FileCopyrightText: Petr Spirka <petrspirka@gmail.com>
    SPDX-License-Identifier: AGPL-3.0-or-later
    -->
	<div class="max-wh"
		@drop.prevent
		@dragover.prevent
		@dragleave="onDragEnd"
		@dragenter="onDragStart">
		<div class="max-wh"
			@dragleave.prevent
			@dragenter.prevent
			@drop="fileDropped">
			<label id="upload-file-label"
				class="upload-file-label max-wh column"
				for="files">
				<FileUpload :size="128"
					class="hover-pointer" />
				{{ t('thesistemplater', 'Upload files') }}
			</label>
		</div>
		<input id="files"
			class="hidden"
			name="files"
			type="file"
			accept="text/csv"
			multiple
			@change="fileAdded">
	</div>
</template>
<script>
import FileUpload from 'vue-material-design-icons/FileUpload.vue'
import { showError, showSuccess } from '@nextcloud/dialogs'
import '@nextcloud/dialogs/style.css'

const FILE_LIMIT = 10 * 1024 * 1024 // 10 MB

export default {
	name: 'FileDropInput',
	components: {
		FileUpload,
	},
	props: {
		text: {
			required: true,
			type: String,
		},
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
		fileAdded(event) {
			const files = event.target.files
			this.uploadFiles(files)
		},

		onDragStart(event) {
			document.getElementById('upload-file-label').classList.add('drag-file')
		},

		onDragEnd(event) {
			document.getElementById('upload-file-label').classList.remove('drag-file')
		},

		fileDropped(event) {
			const files = event.dataTransfer.files
			this.uploadFiles(files)
		},

		uploadFiles(files) {
			let valid = true
			for (const file of files) {
				if (file.size > FILE_LIMIT) {
					showError(t('thesistemplater', 'File {file} is too big. Max file size is {maxFileSize} MB', { file: file.name, maxFileSize: FILE_LIMIT / 1024 / 1024 }))
					valid = false
					break
				}
			}
			showSuccess(t('thesistemplater', 'File uploaded'))
			if (valid) {
				this.$emit('uploaded', files)
			}
		},
	},
}
</script>
<style scoped>
	.upload-file-label {
		display:flex;
		justify-content: center;
		background-color: rgb(40, 40, 40);
	}

	.hover-pointer {
		cursor: pointer;
	}

	.max-wh {
		width: 100%;
		height: 100%;
	}

	.column {
		display: flex;
		flex-direction: column;
		align-items: center;
	}
</style>
